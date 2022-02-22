<?php

namespace Dotsquare\Giftcard\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class ProductName
 *
 * @package Dotsquare\Giftcard\Ui\Component\Listing\Column
 */
class ProductName extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('config/fieldName');
        $dsgcBackUrlParam = $this->getData('config/dsgcBackUrlParam')
            ? ['dsgcBack' => $this->getData('config/dsgcBackUrlParam')]
            : [];
        $columnName = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            if ($productId = $item[$fieldName]) {
                if ($productName = $item[$columnName]) {
                    $item[$columnName . '_url'] = $this->context->getUrl(
                        'ds_giftcard_admin/product/edit',
                        array_merge(['id' => $productId], $dsgcBackUrlParam)
                    );
                    $item[$columnName . '_label'] = $productName;
                } else {
                    $item[$columnName . '_label'] = $productId;
                }
            }
        }
        return $dataSource;
    }
}
