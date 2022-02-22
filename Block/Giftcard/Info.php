<?php

namespace Dotsquare\Giftcard\Block\Giftcard;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Dotsquare\Giftcard\Model\Source\Giftcard\Status;
use Magento\Framework\View\Element\Template;
use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Info
 *
 * @package Dotsquare\Giftcard\Block\Giftcard
 * @method \Dotsquare\Giftcard\Block\Giftcard\Info setGiftcard(GiftcardInterface $value)
 * @method GiftcardInterface getGiftcard()
 */
class Info extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Dotsquare_Giftcard::giftcard/info.phtml';

    /**
     * @var Status
     */
    private $sourceStatus;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @param Context $context
     * @param Status $sourceStatus
     * @param PriceCurrencyInterface $priceCurrency
     * @param ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Status $sourceStatus,
        PriceCurrencyInterface $priceCurrency,
        ManagerInterface $messageManager,
        array $data = []
    ) {
        $this->sourceStatus = $sourceStatus;
        $this->priceCurrency = $priceCurrency;
        $this->messageManager = $messageManager;
        parent::__construct($context, $data);
    }

    /**
     * Format price
     *
     * @param float $amount
     * @return float
     */
    public function formatPrice($amount)
    {
        return $this->priceCurrency->convertAndFormat($amount);
    }

    /**
     * Format Gift Card code status
     *
     * @param int $state
     * @return string
     */
    public function formatState($state)
    {
        return $this->sourceStatus->getOptionByValue($state);
    }

    /**
     * Format Gift Card code status
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getMessages()
    {
        return $this->messageManager->getMessages(true)->getItems();
    }
}
