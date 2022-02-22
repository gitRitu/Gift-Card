<?php

namespace Dotsquare\Giftcard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Giftcard
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel
 */
class Giftcard extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard', 'id');
    }
}
