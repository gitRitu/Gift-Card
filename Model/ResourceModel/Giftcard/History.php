<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class History
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel
 */
class History extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_history', 'id');
    }
}
