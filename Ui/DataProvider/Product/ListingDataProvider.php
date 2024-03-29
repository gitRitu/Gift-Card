<?php

namespace Dotsquare\Giftcard\Ui\DataProvider\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Dotsquare\Giftcard\Model\ResourceModel\Product\CollectionFactory as GiftcardProductCollectionFactory;
use Dotsquare\Giftcard\Model\ResourceModel\Product\Collection;

/**
 * Class ListingDataProvider
 *
 * @package Dotsquare\Giftcard\Ui\DataProvider\Product
 */
class ListingDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ProductCollectionFactory $productCollectionFactory
     * @param GiftcardProductCollectionFactory $giftcardProductCollectionFactory
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[] $addFieldStrategies
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[] $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ProductCollectionFactory $productCollectionFactory,
        GiftcardProductCollectionFactory $giftcardProductCollectionFactory,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $productCollectionFactory,
            $addFieldStrategies,
            $addFilterStrategies,
            $meta,
            $data
        );
        $this->collection = $giftcardProductCollectionFactory->create();
    }
}
