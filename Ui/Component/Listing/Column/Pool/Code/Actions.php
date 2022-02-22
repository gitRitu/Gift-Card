<?php

namespace Dotsquare\Giftcard\Ui\Component\Listing\Column\Pool\Code;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 *
 * @package Dotsquare\Giftcard\Ui\Component\Listing\Column\Pool\Code
 */
class Actions extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        foreach ($dataSource['data']['items'] as & $item) {
            $item[$this->getData('name')] = [
                'delete' => [
                    'href' => $this->context->getUrl(
                        'ds_giftcard_admin/pool/code_delete',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete "${ $.$data.code }"'),
                        'message' => __('Are you sure you want to delete a "${ $.$data.code }" code?')
                    ]
                ]
            ];
        }

        return $dataSource;
    }
}
