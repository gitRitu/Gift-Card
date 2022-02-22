<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dotsquare\Giftcard\Model\Giftcard\Order;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Order as ResourceOrder;

/**
 * Class Collection
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Order
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Order::class, ResourceOrder::class);
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
