<?php

namespace Dotsquare\Giftcard\Model\Product;

use Dotsquare\Giftcard\Api\Data\OptionInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Option
 *
 * @package Dotsquare\Giftcard\Model\Product
 */
class Option extends AbstractExtensibleModel implements OptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDsGcAmount()
    {
        return $this->getData(self::AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcAmount($amount)
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcCustomAmount()
    {
        return $this->getData(self::CUSTOM_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcCustomAmount($amount)
    {
        return $this->setData(self::CUSTOM_AMOUNT, $amount);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcTemplate()
    {
        return $this->getData(self::TEMPLATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcTemplate($template)
    {
        return $this->setData(self::TEMPLATE, $template);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcTemplateName()
    {
        return $this->getData(self::TEMPLATE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcTemplateName($templateName)
    {
        return $this->setData(self::TEMPLATE_NAME, $templateName);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcSenderName()
    {
        return $this->getData(self::SENDER_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcSenderName($senderName)
    {
        return $this->setData(self::SENDER_NAME, $senderName);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcSenderEmail()
    {
        return $this->getData(self::SENDER_EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcSenderEmail($senderEmail)
    {
        return $this->setData(self::SENDER_EMAIL, $senderEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcRecipientName()
    {
        return $this->getData(self::RECIPIENT_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcRecipientName($recipientName)
    {
        return $this->setData(self::RECIPIENT_NAME, $recipientName);
    }

    /**
     * Get recipient email
     *
     * @return string
     */
    public function getDsGcRecipientEmail()
    {
        return $this->getData(self::RECIPIENT_EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcRecipientEmail($recipientEmail)
    {
        return $this->setData(self::RECIPIENT_EMAIL, $recipientEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcHeadline()
    {
        return $this->getData(self::HEADLINE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcHeadline($headline)
    {
        return $this->setData(self::HEADLINE, $headline);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcType()
    {
        return $this->getData(self::GIFTCARD_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcType($giftcardType)
    {
        return $this->setData(self::GIFTCARD_TYPE, $giftcardType);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcDeliveryDate()
    {
        return $this->getData(self::DELIVERY_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcDeliveryDate($deliveryDate)
    {
        return $this->setData(self::DELIVERY_DATE, $deliveryDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcDeliveryDateTimezone()
    {
        return $this->getData(self::DELIVERY_DATE_TIMEZONE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcDeliveryDateTimezone($deliveryDateTimezone)
    {
        return $this->setData(self::DELIVERY_DATE_TIMEZONE, $deliveryDateTimezone);
    }

    /**
     * {@inheritdoc}
     */
    public function getDsGcCreatedCodes()
    {
        return $this->getData(self::GIFTCARD_CODES);
    }

    /**
     * {@inheritdoc}
     */
    public function setDsGcCreatedCodes($giftcardCodes)
    {
        return $this->setData(self::GIFTCARD_CODES, $giftcardCodes);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Dotsquare\Giftcard\Api\Data\OptionExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
