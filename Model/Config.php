<?php

namespace Dotsquare\Giftcard\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config
 *
 * @package Dotsquare\Giftcard\Model
 */
class Config
{
    /**#@+
     * Constants for config path
     */
    const XML_PATH_GIFTCARD_EXPIRE_DAYS = 'ds_giftcard/general/expire_days';
    const XML_PATH_EMAIL_SENDER = 'ds_giftcard/email/sender';
    const XML_PATH_GIFTCARD_CODE_LENGTH = 'ds_giftcard/code_pattern/code_length';
    const XML_PATH_GIFTCARD_CODE_FORMAT = 'ds_giftcard/code_pattern/code_format';
    const XML_PATH_GIFTCARD_CODE_PREFIX = 'ds_giftcard/code_pattern/code_prefix';
    const XML_PATH_GIFTCARD_CODE_SUFFIX = 'ds_giftcard/code_pattern/code_suffix';
    const XML_PATH_GIFTCARD_CODE_DASH_EVERY_X_CHARACTERS = 'ds_giftcard/code_pattern/dash_every_x_characters';
    const XML_PATH_GENERAL_LOCALE_CODE = 'general/locale/code';
    const XML_PATH_GENERAL_LOCALE_TIMEZONE = 'general/locale/timezone';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Gift Card expire days
     *
     * @param int|null $websiteId
     * @return int
     */
    public function getGiftcardExpireDays($websiteId = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_EXPIRE_DAYS,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get email sender
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEmailSender($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get email sender name
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEmailSenderName($storeId = null)
    {
        $sender = $this->getEmailSender($storeId);
        return $this->scopeConfig->getValue(
            'trans_email/ident_' . $sender . '/name',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Gift Card code length
     *
     * @param int|null $websiteId
     * @return int
     */
    public function getGiftcardCodeLength($websiteId = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_LENGTH,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code format
     *
     * @param string|null $websiteId
     * @return int
     */
    public function getGiftcardCodeFormat($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_FORMAT,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code prefix
     *
     * @param string|null $websiteId
     * @return int
     */
    public function getGiftcardCodePrefix($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_PREFIX,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code suffix
     *
     * @param string|null $websiteId
     * @return int
     */
    public function getGiftcardCodeSuffix($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_SUFFIX,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get Gift Card code dash every X characters
     *
     * @param int|null $websiteId
     * @return int
     */
    public function getGiftcardCodeDashAtEvery($websiteId = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_GIFTCARD_CODE_DASH_EVERY_X_CHARACTERS,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
