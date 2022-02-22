<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Creditmemo;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dotsquare\Giftcard\Model\Giftcard\Creditmemo;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Creditmemo as ResourceCreditmemo;

/**
 * Class Collection
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Creditmemo
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Creditmemo::class, ResourceCreditmemo::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->joinLeft(
                ['giftcard' => $this->getTable('ds_giftcard')],
                'main_table.giftcard_id = giftcard.id',
                [
                    'giftcard_code' => 'giftcard.code'
                ]
            );
        $this->addFilterToMap('order_id', 'main_table.order_id');
        return $this;
    }
}
