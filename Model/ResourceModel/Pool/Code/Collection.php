<?php
namespace Dotsquare\Giftcard\Model\ResourceModel\Pool\Code;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dotsquare\Giftcard\Model\Pool\Code;
use Dotsquare\Giftcard\Model\ResourceModel\Pool\Code as ResourceCode;

/**
 * Class Collection
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Pool\Code
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
        $this->_init(Code::class, ResourceCode::class);
    }
}
