<?php

namespace Dotsquare\Giftcard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Pool
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel
 */
class Pool extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_pool', 'id');
    }
}
