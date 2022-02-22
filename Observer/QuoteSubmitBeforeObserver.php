<?php

namespace Dotsquare\Giftcard\Observer;

use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterfaceFactory as GiftcardOrderInterfaceFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class QuoteSubmitBeforeObserver
 *
 * @package Dotsquare\Giftcard\Observer
 */
class QuoteSubmitBeforeObserver implements ObserverInterface
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var GiftcardOrderInterfaceFactory
     */
    private $giftcardOrderFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param GiftcardOrderInterfaceFactory $giftcardOrderFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        GiftcardOrderInterfaceFactory $giftcardOrderFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->giftcardOrderFactory = $giftcardOrderFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        if ($quote->getBaseDsGiftcardAmount() && $quote->getBaseDsGiftcardAmount() > 0) {
            $order->setBaseDsGiftcardAmount($quote->getBaseDsGiftcardAmount());
            $order->setDsGiftcardAmount($quote->getDsGiftcardAmount());

            $extensionAttributes = $order->getExtensionAttributes()
                ? $order->getExtensionAttributes()
                : $this->orderExtensionFactory->create();

            $quoteGiftcards = [];
            if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getDsGiftcardCodes()) {
                $quoteGiftcards = $quote->getExtensionAttributes()->getDsGiftcardCodes();
            }
            $orderGiftcards = [];
            /** @var GiftcardQuoteInterface $quoteGiftcard */
            foreach ($quoteGiftcards as $quoteGiftcard) {
                /** @var GiftcardOrderInterface $orderGiftcard */
                $orderGiftcard = $this->giftcardOrderFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $orderGiftcard,
                    $this->dataObjectProcessor->buildOutputDataArray($quoteGiftcard, QuoteInterface::class),
                    GiftcardOrderInterface::class
                );
                $orderGiftcard->setId(null);
                $orderGiftcards[] = $orderGiftcard;
            }
            $extensionAttributes->setDsGiftcardCodes($orderGiftcards);
            $order->setExtensionAttributes($extensionAttributes);
        }
    }
}
