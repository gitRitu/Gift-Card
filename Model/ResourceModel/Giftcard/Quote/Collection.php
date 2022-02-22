<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Quote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dotsquare\Giftcard\Model\Giftcard\Quote;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Quote as ResourceQuote;

/**
 * Class Collection
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Quote
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Quote::class, ResourceQuote::class);
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
                    'giftcard_code' => 'giftcard.code',
                    'giftcard_balance' => 'giftcard.balance'
                ]
            );
        return $this;
    }
}
