<?php

namespace Dotsquare\Giftcard\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface OptionInterface
 * @api
 */
interface OptionInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const AMOUNT = 'ds_gc_amount';
    const CUSTOM_AMOUNT = 'ds_gc_custom_amount';
    const TEMPLATE = 'ds_gc_template';
    const TEMPLATE_NAME = 'ds_gc_template_name';
    const RECIPIENT_NAME = 'ds_gc_recipient_name';
    const RECIPIENT_EMAIL = 'ds_gc_recipient_email';
    const SENDER_NAME = 'ds_gc_sender_name';
    const SENDER_EMAIL = 'ds_gc_sender_email';
    const HEADLINE = 'ds_gc_headline';
    const MESSAGE = 'ds_gc_message';
    const GIFTCARD_TYPE = 'ds_gc_type';
    const DELIVERY_DATE = 'ds_gc_delivery_date';
    const DELIVERY_DATE_TIMEZONE = 'ds_gc_delivery_date_timezone';
    const GIFTCARD_CODES = 'ds_gc_created_codes';
    /**#@-*/

    /**
     * Get amount
     *
     * @return float|null
     */
    public function getDsGcAmount();

    /**
     * Set amount
     *
     * @param float|null $amount
     * @return $this
     */
    public function setDsGcAmount($amount);

    /**
     * Get custom amount
     *
     * @return float|null
     */
    public function getDsGcCustomAmount();

    /**
     * Set custom amount
     *
     * @param float|null $amount
     * @return $this
     */
    public function setDsGcCustomAmount($amount);

    /**
     * Get template
     *
     * @return string
     */
    public function getDsGcTemplate();

    /**
     * Set template
     *
     * @param string $template
     * @return $this
     */
    public function setDsGcTemplate($template);

    /**
     * Get template name
     *
     * @return int
     */
    public function getDsGcTemplateName();

    /**
     * Set template name
     *
     * @param int $templateName
     * @return $this
     */
    public function setDsGcTemplateName($templateName);

    /**
     * Get sender name
     *
     * @return string
     */
    public function getDsGcSenderName();

    /**
     * Set sender name
     *
     * @param string $senderName
     * @return $this
     */
    public function setDsGcSenderName($senderName);

    /**
     * Get sender email
     *
     * @return string
     */
    public function getDsGcSenderEmail();

    /**
     * Set sender email
     *
     * @param string $senderEmail
     * @return $this
     */
    public function setDsGcSenderEmail($senderEmail);

    /**
     * Get recipient name
     *
     * @return string
     */
    public function getDsGcRecipientName();

    /**
     * Set recipient name
     *
     * @param string $recipientName
     * @return $this
     */
    public function setDsGcRecipientName($recipientName);

    /**
     * Get recipient email
     *
     * @return string
     */
    public function getDsGcRecipientEmail();

    /**
     * Set recipient email
     *
     * @param string $recipientEmail
     * @return $this
     */
    public function setDsGcRecipientEmail($recipientEmail);

    /**
     * Get headline
     *
     * @return string|null
     */
    public function getDsGcHeadline();

    /**
     * Set headline
     *
     * @param string|null $headline
     * @return $this
     */
    public function setDsGcHeadline($headline);

    /**
     * Get message
     *
     * @return string|null
     */
    public function getDsGcMessage();

    /**
     * Set message
     *
     * @param string|null $message
     * @return $this
     */
    public function setDsGcMessage($message);

    /**
     * Get gift card type
     *
     * @return int
     */
    public function getDsGcType();

    /**
     * Set gift card type
     *
     * @param int $giftcardType
     * @return $this
     */
    public function setDsGcType($giftcardType);

    /**
     * Get gift card delivery date
     *
     * @return string
     */
    public function getDsGcDeliveryDate();

    /**
     * Set gift card delivery date
     *
     * @param string $deliveryDate
     * @return $this
     */
    public function setDsGcDeliveryDate($deliveryDate);

    /**
     * Get gift card delivery date timezone
     *
     * @return string
     */
    public function getDsGcDeliveryDateTimezone();

    /**
     * Set gift card delivery date timezone
     *
     * @param string $deliveryDateTimezone
     * @return $this
     */
    public function setDsGcDeliveryDateTimezone($deliveryDateTimezone);

    /**
     * Get gift card codes
     *
     * @return string[]
     */
    public function getDsGcCreatedCodes();

    /**
     * Set gift card codes
     *
     * @param string[] $giftcardCodes
     * @return $this
     */
    public function setDsGcCreatedCodes($giftcardCodes);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Dotsquare\Giftcard\Api\Data\OptionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Dotsquare\Giftcard\Api\Data\OptionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Dotsquare\Giftcard\Api\Data\OptionExtensionInterface $extensionAttributes
    );
}
