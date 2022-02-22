<?php

namespace Dotsquare\Giftcard\Model\Sales\Totals;

use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Dotsquare\Giftcard\Model\Giftcard\Validator\Quote as GiftcardQuoteValidator;
use Dotsquare\Giftcard\Model\Product\Type\Giftcard as GiftcardProductType;
use Dotsquare\Giftcard\Model\Sales\Totals\Calculator\GiftCardExclude;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote as ModelQuote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

/**
 * Class Quote
 *
 * @package Dotsquare\Giftcard\Model\Sales\Totals
 */
class Quote extends AbstractTotal
{
    /**
     * @var bool
     */
    private $isFirstTimeResetRun = true;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var GiftcardQuoteValidator
     */
    private $giftcardQuoteValidator;

    /**
     * @var GiftCardExclude
     */
    private $excludeCalculator;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param GiftcardQuoteValidator $giftcardQuoteValidator
     * @param GiftCardExclude $excludeCalculator
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        GiftcardQuoteValidator $giftcardQuoteValidator,
        GiftCardExclude $excludeCalculator
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->giftcardQuoteValidator = $giftcardQuoteValidator;
        $this->excludeCalculator = $excludeCalculator;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(
        ModelQuote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $address = $shippingAssignment->getShipping()->getAddress();
        $items = $shippingAssignment->getItems();
        $this->reset($total, $quote, $address);

        if (!count($items)) {
            return $this;
        }

        $baseGrandTotal = $total->getBaseGrandTotal();
        $grandTotal = $total->getGrandTotal();

        if (!$quote->getExtensionAttributes()
            || ($quote->getExtensionAttributes() && !$quote->getExtensionAttributes()->getDsGiftcardCodes())
            || !$baseGrandTotal
        ) {
            $this->reset($total, $quote, $address, true);
            return $this;
        }

        $baseTotalGiftcardAmount = $totalGiftcardAmount = 0;
        $giftcards = $quote->getExtensionAttributes()->getDsGiftcardCodes();

        if ($giftcards) {
            list($baseGrandTotal, $grandTotal) = $this->excludeCalculator->calculate(
                $quote->getItems(),
                $baseGrandTotal,
                $grandTotal
            );
        }

        /** @var $giftcard GiftcardQuoteInterface */
        foreach ($giftcards as $giftcard) {
            if ($giftcard->isRemove()) {
                continue;
            }
            $giftcardCode = $giftcard->getGiftcardCode();
            $websiteId = $quote->getStore()->getWebsiteId();
            if ($this->giftcardQuoteValidator->isValid($giftcardCode, $websiteId) == false) {
                $giftcard->setIsRemove(true);
                $giftcard->setIsInvalid(true);
                continue;
            }
            $baseGiftcardUsedAmount = min($giftcard->getGiftcardBalance(), $baseGrandTotal);
            $baseGrandTotal -= $baseGiftcardUsedAmount;

            $giftcardUsedAmount = min($this->priceCurrency->convert($baseGiftcardUsedAmount), $grandTotal);
            $grandTotal -= $giftcardUsedAmount;

            $baseTotalGiftcardAmount += $baseGiftcardUsedAmount;
            $totalGiftcardAmount += $giftcardUsedAmount;

            if ($baseGiftcardUsedAmount <= 0) {
                $giftcard->setIsRemove(true);
            } else {
                $giftcard
                    ->setBaseGiftcardAmount($baseGiftcardUsedAmount)
                    ->setGiftcardAmount($giftcardUsedAmount);
            }
        }
        $this
            ->_addBaseAmount($baseTotalGiftcardAmount)
            ->_addAmount($totalGiftcardAmount);
        $total
            ->setBaseDsGiftcardAmount($baseTotalGiftcardAmount)
            ->setDsGiftcardAmount($totalGiftcardAmount)
            ->setBaseGrandTotal($total->getBaseGrandTotal() - $baseTotalGiftcardAmount)
            ->setGrandTotal($total->getGrandTotal() - $totalGiftcardAmount);
        $quote
            ->setBaseDsGiftcardAmount($baseTotalGiftcardAmount)
            ->setDsGiftcardAmount($totalGiftcardAmount);
        $address
            ->setBaseDsGiftcardAmount($baseTotalGiftcardAmount)
            ->setDsGiftcardAmount($totalGiftcardAmount);

        return $this;
    }

    /**
     * Reset Gift Crad total
     *
     * @param Total $total
     * @param ModelQuote $quote
     * @param AddressInterface $address
     * @param bool $reset
     * @return $this
     */
    private function reset(Total $total, ModelQuote $quote, AddressInterface $address, $reset = false)
    {
        if ($this->isFirstTimeResetRun || $reset) {
            $this->_addAmount(0);
            $this->_addBaseAmount(0);

            $total->setBaseDsGiftcardAmount(0);
            $total->setDsGiftcardAmount(0);

            $quote->setBaseDsGiftcardAmount(0);
            $quote->setDsGiftcardAmount(0);

            $address->setBaseDsGiftcardAmount(0);
            $address->setDsGiftcardAmount(0);

            if ($reset && $quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getDsGiftcardCodes()) {
                $giftcards = $quote->getExtensionAttributes()->getDsGiftcardCodes();
                /** @var $giftcard GiftcardQuoteInterface */
                foreach ($giftcards as $giftcard) {
                    $giftcard->setIsRemove(true);
                }
            }

            $this->isFirstTimeResetRun = false;
        }
        return $this;
    }

    /**
     * Add Gift Card
     *
     * @param ModelQuote $quote
     * @param Total $total
     * @return []
     */
    public function fetch(ModelQuote $quote, Total $total)
    {
        $giftcards = [];
        if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getDsGiftcardCodes()) {
            $giftcards = $quote->getExtensionAttributes()->getDsGiftcardCodes();
        }
        if (!empty($giftcards)) {
            return [
                'code' => $this->getCode(),
                'ds_giftcard_codes' => $giftcards,
                'title' => __('Gift Card'),
                'value' => -$total->getDsGiftcardAmount()
            ];
        }

        return null;
    }
}
