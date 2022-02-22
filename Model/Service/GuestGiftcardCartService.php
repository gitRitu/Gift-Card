<?php

namespace Dotsquare\Giftcard\Model\Service;

use Dotsquare\Giftcard\Api\GiftcardCartManagementInterface;
use Dotsquare\Giftcard\Api\GuestGiftcardCartManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestGiftcardCartService
 *
 * @package Dotsquare\Giftcard\Model\Service
 */
class GuestGiftcardCartService implements GuestGiftcardCartManagementInterface
{
    /**
     * @var GiftcardCartManagementInterface
     */
    private $giftcardCartManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param GiftcardCartManagementInterface $giftcardCartManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        GiftcardCartManagementInterface $giftcardCartManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->giftcardCartManagement = $giftcardCartManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($cartId)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->giftcardCartManagement->get($quoteIdMask->getQuoteId());
    }

    /**
     * {@inheritdoc}
     */
    public function set($cartId, $giftcardCode)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->giftcardCartManagement->set($quoteIdMask->getQuoteId(), $giftcardCode);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($cartId, $giftcardCode)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->giftcardCartManagement->remove($quoteIdMask->getQuoteId(), $giftcardCode);
    }
}
