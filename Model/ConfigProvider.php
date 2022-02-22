<?php

namespace Dotsquare\Giftcard\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;

/**
 * Class ConfigProvider
 *
 * @package Dotsquare\Giftcard\Model
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return [
            'dsGiftcard' => [
                'removeUrl' => $this->getRemoveUrl()
            ]
        ];
    }

    /**
     * Get controller URL to remove Gift Card code on cart page
     *
     * @return string
     */
    private function getRemoveUrl()
    {
        return $this->urlBuilder->getUrl('dsgiftcard/cart/remove');
    }
}
