<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Creditmemo
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard
 */
class Creditmemo extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_creditmemo', 'id');
    }
}
