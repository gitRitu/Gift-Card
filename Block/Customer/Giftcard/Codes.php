<?php

namespace Dotsquare\Giftcard\Block\Customer\Giftcard;

use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Dotsquare\Giftcard\Api\GiftcardManagementInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class Codes
 *
 * @package Dotsquare\Giftcard\Block\Customer\Giftcard
 */
class Codes extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Dotsquare_Giftcard::customer/giftcard/codes.phtml';

    /**
     * @var GiftcardManagementInterface
     */
    private $giftcardManagement;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param Context $context
     * @param GiftcardManagementInterface $giftcardManagement
     * @param CustomerSession $customerSession
     * @param CheckoutSession $checkoutSession
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        Context $context,
        GiftcardManagementInterface $giftcardManagement,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->giftcardManagement = $giftcardManagement;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    /**
     * Check is account page or not
     *
     * @return bool
     */
    public function isAccountPage()
    {
        return $this->_request->getModuleName() == 'dsgiftcard' && $this->_request->getControllerName() == 'card';
    }

    /**
     * Retrieve customer Gift Card codes
     *
     * @return GiftcardInterface[]
     */
    public function getCustomerGiftcardCodes()
    {
        $giftcardCodes = [];
        if ($this->customerSession->isLoggedIn()) {
            $cartId = $this->isAccountPage()
                ? null
                : $this->checkoutSession->getQuoteId();
            $giftcardCodes = $this->giftcardManagement->getCustomerGiftcards(
                $this->customerSession->getCustomer()->getEmail(),
                $cartId
            );
        }
        return $giftcardCodes;
    }

    /**
     * Format price
     *
     * @param float $amount
     * @return float
     */
    public function formatPrice($amount)
    {
        return $this->priceCurrency->format($amount);
    }

    /**
     * Retrieve check Gift Card code url
     *
     * @return string
     */
    public function getCheckCodeUrl()
    {
        return $this->getUrl('dsgiftcard/card/checkCode');
    }

    /**
     * Retrieve apply url
     *
     * @return string
     */
    public function getApplyUrl()
    {
        return $this->getUrl('dsgiftcard/cart/apply');
    }
}
