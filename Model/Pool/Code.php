<?php

namespace Dotsquare\Giftcard\Model\Pool;

use Dotsquare\Giftcard\Api\Data\Pool\CodeInterface;
use Magento\Framework\Model\AbstractModel;
use Dotsquare\Giftcard\Model\ResourceModel\Pool\Code as ResourceCode;

/**
 * Class Code
 *
 * @package Dotsquare\Giftcard\Model
 */
class Code extends AbstractModel implements CodeInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceCode::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getPoolId()
    {
        return $this->getData(self::POOL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setPoolId($poolId)
    {
        return $this->setData(self::POOL_ID, $poolId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function isUsed()
    {
        return $this->getData(self::USED);
    }

    /**
     * {@inheritdoc}
     */
    public function setUsed($used)
    {
        return $this->setData(self::USED, $used);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Dotsquare\Giftcard\Api\Data\Pool\CodeExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
