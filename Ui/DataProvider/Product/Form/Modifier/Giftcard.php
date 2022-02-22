<?php

namespace Dotsquare\Giftcard\Ui\DataProvider\Product\Form\Modifier;

use Dotsquare\Giftcard\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;

/**
 * Class Giftcard
 *
 * @package Dotsquare\Giftcard\Ui\DataProvider\Product\Form\Modifier
 */
class Giftcard extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $poolFieldPath = $this->arrayManager
            ->findPath(ProductAttributeInterface::CODE_DS_GC_POOL, $meta, null, 'children');

        if ($poolFieldPath) {
            $usedDefault = $this->locator->getProduct()->getData(ProductAttributeInterface::CODE_DS_GC_POOL) === null;

            $meta = $this->arrayManager->merge(
                $poolFieldPath,
                $meta,
                [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'service' => [
                                    'template' =>
                                        'Dotsquare_Giftcard/ui/form/element/giftcard/helper/service-settings',
                                    'configSettingsUrl' =>
                                        $this->urlBuilder->getUrl('adminhtml/system_config/edit/section/ds_giftcard'),
                                    'label' => __('Use pattern from')
                                ],
                                'usedDefault' => $usedDefault,
                                'disabled' => $usedDefault,
                                'validation' => [
                                    'validate-select' => true
                                ]
                            ],
                        ],
                    ]
                ]
            );
        }

        return $meta;
    }
}
