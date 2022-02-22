<?php

namespace Dotsquare\Giftcard\Ui\DataProvider\Pool;

use Dotsquare\Giftcard\Model\ResourceModel\Pool\CollectionFactory;
use Dotsquare\Giftcard\Model\ResourceModel\Pool\Collection;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class FormDataProvider
 *
 * @package Dotsquare\Giftcard\Model\Pool
 */
class FormDataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get('ds_giftcard_pool');
        if (!empty($dataFromForm)) {
            $data[$dataFromForm['id']] = $dataFromForm;
            $this->dataPersistor->clear('ds_giftcard_pool');
        } else {
            $id = $this->request->getParam($this->getRequestFieldName());
            if ($id) {
                $pools = $this->getCollection()->addFieldToFilter('id', $id)->getItems();
                /** @var \Dotsquare\Giftcard\Model\Pool $pool */
                foreach ($pools as $pool) {
                    if ($id == $pool->getId()) {
                        $data[$id] = $pool->getData();
                    }
                }
            }
        }

        return $data;
    }
}
