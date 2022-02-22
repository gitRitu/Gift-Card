<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Pool;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dotsquare\Giftcard\Model\Pool;
use Dotsquare\Giftcard\Model\ResourceModel\Pool as ResourcePool;

/**
 * Class Collection
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Pool
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Pool::class, ResourcePool::class);
    }
}
