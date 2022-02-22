<?php

namespace Dotsquare\Giftcard\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface AmountInterface
 * @api
 */
interface AmountInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const VALUE_ID = 'value_id';
    const ENTITY_ID = 'entity_id';
    const VALUE = 'value';
    const WEBSITE_ID = 'website_id';
    /**#@-*/

    /**
     * Get value id
     *
     * @return int
     */
    public function getValueId();

    /**
     * Set value id
     *
     * @param int $valueId
     * @return $this
     */
    public function setValueId($valueId);

    /**
     * Get entity id
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get value
     *
     * @return string
     */
    public function getValue();

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Dotsquare\Giftcard\Api\Data\AmountExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Dotsquare\Giftcard\Api\Data\AmountExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Dotsquare\Giftcard\Api\Data\AmountExtensionInterface $extensionAttributes
    );
}
