<?php

namespace Dotsquare\Giftcard\Model\Sales\Totals;

use Dotsquare\Giftcard\Model\Product\Type\Giftcard as GiftcardProductType;
use Dotsquare\Giftcard\Model\Sales\Totals\Calculator\GiftCardExclude;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Magento\Sales\Model\Order\Invoice as ModelInvoice;
use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\InvoiceInterface as GiftcardInvoiceInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\InvoiceInterfaceFactory as GiftcardInvoiceInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Invoice
 *
 * @package Dotsquare\Giftcard\Model\Sales\Totals
 */
class Invoice extends AbstractTotal
{
    /**
     * @var InvoiceExtensionFactory
     */
    private $invoiceExtensionFactory;

    /**
     * @var GiftcardInvoiceInterfaceFactory
     */
    private $giftcardInvoiceFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var GiftCardExclude
     */
    private $excludeCalculator;

    /**
     * @param InvoiceExtensionFactory $invoiceExtensionFactory
     * @param GiftcardInvoiceInterfaceFactory $giftcardInvoiceFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param GiftCardExclude $excludeCalculator
     * @param array $data
     */
    public function __construct(
        InvoiceExtensionFactory $invoiceExtensionFactory,
        GiftcardInvoiceInterfaceFactory $giftcardInvoiceFactory,
        DataObjectHelper $dataObjectHelper,
        GiftCardExclude $excludeCalculator,
        $data = []
    ) {
        parent::__construct($data);
        $this->invoiceExtensionFactory = $invoiceExtensionFactory;
        $this->giftcardInvoiceFactory = $giftcardInvoiceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->excludeCalculator = $excludeCalculator;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(ModelInvoice $invoice)
    {
        parent::collect($invoice);
        $invoice->setDsGiftcardAmount(0);
        $invoice->setBaseDsGiftcardAmount(0);

        $order = $invoice->getOrder();
        if ($order->getBaseDsGiftcardAmount()
            && $order->getBaseDsGiftcardInvoiced() != $order->getBaseDsGiftcardAmount()
            && $order->getExtensionAttributes() && $order->getExtensionAttributes()->getDsGiftcardCodes()
        ) {
            $baseTotalGiftcardAmount = $totalGiftcardAmount = 0;
            $baseGrandTotal = $invoice->getBaseGrandTotal();
            $grandTotal = $invoice->getGrandTotal();

            $extensionAttributes = $invoice->getExtensionAttributes()
                ? $invoice->getExtensionAttributes()
                : $this->invoiceExtensionFactory->create();
            $orderGiftcards = $order->getExtensionAttributes()->getDsGiftcardCodes();
            $invoicedGiftcards = $order->getExtensionAttributes()->getDsGiftcardCodesInvoiced() ? : [];

            if ($orderGiftcards) {
                list($baseGrandTotal, $grandTotal) = $this->excludeCalculator->calculate(
                    $order->getItems(),
                    $baseGrandTotal,
                    $grandTotal
                );
            }

            $toInvoiceGiftcards = [];
            /** @var GiftcardOrderInterface $orderGiftcard */
            foreach ($orderGiftcards as $orderGiftcard) {
                /** @var GiftcardInvoiceInterface $toInvoiceGiftcard */
                $toInvoiceGiftcard = $this->giftcardInvoiceFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $toInvoiceGiftcard,
                    $orderGiftcard->getData(),
                    GiftcardInvoiceInterface::class
                );
                $toInvoiceGiftcard->setId(null);
                $toInvoiceGiftcard->setOrderId($invoice->getOrderId());

                /** @var GiftcardInvoiceInterface $invoicedGiftcard */
                foreach ($invoicedGiftcards as $invoicedGiftcard) {
                    if ($toInvoiceGiftcard->getGiftcardId() == $invoicedGiftcard->getGiftcardId()) {
                        $toInvoiceGiftcard->setBaseGiftcardAmount(
                            $toInvoiceGiftcard->getBaseGiftcardAmount() - $invoicedGiftcard->getBaseGiftcardAmount()
                        );
                        $toInvoiceGiftcard->setGiftcardAmount(
                            $toInvoiceGiftcard->getGiftcardAmount() - $invoicedGiftcard->getGiftcardAmount()
                        );
                    }
                }
                $baseGiftcardUsedAmount = min($toInvoiceGiftcard->getBaseGiftcardAmount(), $baseGrandTotal);
                $baseGrandTotal -= $baseGiftcardUsedAmount;

                $giftcardUsedAmount = min($toInvoiceGiftcard->getGiftcardAmount(), $grandTotal);
                $grandTotal -= $giftcardUsedAmount;

                $baseTotalGiftcardAmount += $baseGiftcardUsedAmount;
                $totalGiftcardAmount += $giftcardUsedAmount;

                $toInvoiceGiftcard->setBaseGiftcardAmount($baseGiftcardUsedAmount);
                $toInvoiceGiftcard->setGiftcardAmount($giftcardUsedAmount);

                if ($toInvoiceGiftcard->getBaseGiftcardAmount() > 0) {
                    $toInvoiceGiftcards[] = $toInvoiceGiftcard;
                }
            }

            if ($baseTotalGiftcardAmount > 0) {
                $extensionAttributes->setDsGiftcardCodes($toInvoiceGiftcards);
                $invoice->setExtensionAttributes($extensionAttributes);

                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseTotalGiftcardAmount);
                $invoice->setGrandTotal($invoice->getGrandTotal() - $totalGiftcardAmount);

                $invoice->setBaseDsGiftcardAmount($baseTotalGiftcardAmount);
                $invoice->setDsGiftcardAmount($totalGiftcardAmount);

                $order->setBaseDsGiftcardInvoiced(
                    $order->getBaseDsGiftcardInvoiced() + $invoice->getBaseDsGiftcardAmount()
                );
                $order->setDsGiftcardInvoiced(
                    $order->getDsGiftcardInvoiced() + $invoice->getDsGiftcardAmount()
                );
            }
        }

        return $this;
    }
}
