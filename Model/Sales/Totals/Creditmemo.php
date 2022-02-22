<?php
namespace Dotsquare\Giftcard\Model\Sales\Totals;

use Dotsquare\Giftcard\Model\Product\Type\Giftcard as GiftcardProductType;
use Dotsquare\Giftcard\Model\Sales\Totals\Calculator\GiftCardExclude;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Magento\Sales\Model\Order\Creditmemo as ModelCreditmemo;
use Magento\Sales\Api\Data\CreditmemoExtensionFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\InvoiceInterface as GiftcardInvoiceInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\CreditmemoInterface as GiftcardCreditmemoInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\CreditmemoInterfaceFactory as GiftcardCreditmemoInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Creditmemo
 *
 * @package Dotsquare\Giftcard\Model\Sales\Totals
 */
class Creditmemo extends AbstractTotal
{
    /**
     * @var CreditmemoExtensionFactory
     */
    private $creditmemoExtensionFactory;

    /**
     * @var GiftcardCreditmemoInterfaceFactory
     */
    private $giftcardCreditmemoFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var GiftCardExclude
     */
    private $excludeCalculator;

    /**
     * @param CreditmemoExtensionFactory $creditmemoExtensionFactory
     * @param GiftcardCreditmemoInterfaceFactory $giftcardCreditmemoFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param GiftCardExclude $excludeCalculator
     * @param array $data
     */
    public function __construct(
        CreditmemoExtensionFactory $creditmemoExtensionFactory,
        GiftcardCreditmemoInterfaceFactory $giftcardCreditmemoFactory,
        DataObjectHelper $dataObjectHelper,
        GiftCardExclude $excludeCalculator,
        $data = []
    ) {
        parent::__construct($data);
        $this->creditmemoExtensionFactory = $creditmemoExtensionFactory;
        $this->giftcardCreditmemoFactory = $giftcardCreditmemoFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->excludeCalculator = $excludeCalculator;
    }

    /**
     * @param ModelCreditmemo $creditmemo
     * @return $this
     */
    public function collect(ModelCreditmemo $creditmemo)
    {
        parent::collect($creditmemo);
        $creditmemo->setDsGiftcardAmount(0);
        $creditmemo->setBaseDsGiftcardAmount(0);

        $order = $creditmemo->getOrder();
        if ($order->getBaseDsGiftcardAmount()
            && $order->getBaseDsGiftcardRefunded() != $order->getBaseDsGiftcardAmount()
            && $order->getExtensionAttributes() && $order->getExtensionAttributes()->getDsGiftcardCodesInvoiced()
        ) {
            $baseTotalGiftcardAmount = $totalGiftcardAmount = 0;
            $baseGrandTotal = $creditmemo->getBaseGrandTotal();
            $grandTotal = $creditmemo->getGrandTotal();

            $extensionAttributes = $creditmemo->getExtensionAttributes()
                ? $creditmemo->getExtensionAttributes()
                : $this->creditmemoExtensionFactory->create();
            $orderGiftcards = $order->getExtensionAttributes()->getDsGiftcardCodes();
            $invoicedGiftcards = $order->getExtensionAttributes()->getDsGiftcardCodesInvoiced();
            $refundedGiftcards = $order->getExtensionAttributes()->getDsGiftcardCodesRefunded() ? : [];

            if ($orderGiftcards) {
                list($baseGrandTotal, $grandTotal) = $this->excludeCalculator->calculate(
                    $order->getItems(),
                    $baseGrandTotal,
                    $grandTotal
                );
            }

            $toRefundGiftcards = [];
            /** @var GiftcardOrderInterface $orderGiftcard */
            foreach ($orderGiftcards as $orderGiftcard) {
                /** @var GiftcardCreditmemoInterface $toRefundGiftcard */
                $toRefundGiftcard = $this->giftcardCreditmemoFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $toRefundGiftcard,
                    $orderGiftcard->getData(),
                    GiftcardCreditmemoInterface::class
                );
                $toRefundGiftcard->setId(null);
                $toRefundGiftcard->setBaseGiftcardAmount(0);
                $toRefundGiftcard->setGiftcardAmount(0);
                $toRefundGiftcard->setOrderId($creditmemo->getOrderId());

                /** @var GiftcardInvoiceInterface $invoicedGiftcard */
                foreach ($invoicedGiftcards as $invoicedGiftcard) {
                    if ($toRefundGiftcard->getGiftcardId() == $invoicedGiftcard->getGiftcardId()) {
                        $toRefundGiftcard->setBaseGiftcardAmount(
                            $toRefundGiftcard->getBaseGiftcardAmount() + $invoicedGiftcard->getBaseGiftcardAmount()
                        );
                        $toRefundGiftcard->setGiftcardAmount(
                            $toRefundGiftcard->getGiftcardAmount() + $invoicedGiftcard->getGiftcardAmount()
                        );
                    }
                }

                /** @var GiftcardCreditmemoInterface $refundedGiftcard */
                foreach ($refundedGiftcards as $refundedGiftcard) {
                    if ($toRefundGiftcard->getGiftcardId() == $refundedGiftcard->getGiftcardId()) {
                        $toRefundGiftcard->setBaseGiftcardAmount(
                            $toRefundGiftcard->getBaseGiftcardAmount() - $refundedGiftcard->getBaseGiftcardAmount()
                        );
                        $toRefundGiftcard->setGiftcardAmount(
                            $toRefundGiftcard->getGiftcardAmount() - $refundedGiftcard->getGiftcardAmount()
                        );
                    }
                }
                $baseGiftcardUsedAmount = min($toRefundGiftcard->getBaseGiftcardAmount(), $baseGrandTotal);
                $baseGrandTotal -= $baseGiftcardUsedAmount;

                $giftcardUsedAmount = min($toRefundGiftcard->getGiftcardAmount(), $grandTotal);
                $grandTotal -= $giftcardUsedAmount;

                $baseTotalGiftcardAmount += $baseGiftcardUsedAmount;
                $totalGiftcardAmount += $giftcardUsedAmount;

                $toRefundGiftcard->setBaseGiftcardAmount($baseGiftcardUsedAmount);
                $toRefundGiftcard->setGiftcardAmount($giftcardUsedAmount);

                if ($toRefundGiftcard->getBaseGiftcardAmount() > 0) {
                    $toRefundGiftcards[] = $toRefundGiftcard;
                }
            }

            if ($baseTotalGiftcardAmount > 0) {
                $extensionAttributes->setDsGiftcardCodes($toRefundGiftcards);
                $creditmemo->setExtensionAttributes($extensionAttributes);

                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseTotalGiftcardAmount);
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $totalGiftcardAmount);

                $creditmemo->setBaseDsGiftcardAmount($baseTotalGiftcardAmount);
                $creditmemo->setDsGiftcardAmount($totalGiftcardAmount);

                $order->setBaseDsGiftcardRefunded(
                    $order->getBaseDsGiftcardRefunded() + $creditmemo->getBaseDsGiftcardAmount()
                );
                $order->setDsGiftcardRefunded(
                    $order->getDsGiftcardRefunded() + $creditmemo->getDsGiftcardAmount()
                );
            }
        }
        return $this;
    }
}
