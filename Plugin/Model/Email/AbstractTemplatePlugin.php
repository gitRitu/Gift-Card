<?php

namespace Dotsquare\Giftcard\Plugin\Model\Email;

use Magento\Framework\App\RequestInterface;
use Magento\Email\Model\AbstractTemplate;
use Magento\Framework\View\Asset\Repository as AssetRepository;

/**
 * Class AbstractTemplatePlugin
 *
 * @package Dotsquare\Giftcard\Plugin\Model\Email
 */
class AbstractTemplatePlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var AssetRepository
     */
    private $assetRepo;

    /**
     * @param RequestInterface $request
     * @param AssetRepository $assetRepo
     */
    public function __construct(
        RequestInterface $request,
        AssetRepository $assetRepo
    ) {
        $this->request = $request;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Replace quote_id in Gift Card quote table after merge quote
     *
     * @param AbstractTemplate $subject
     * @param \Closure $proceed
     * @param [] $variables
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetProcessedTemplate(AbstractTemplate $subject, \Closure $proceed, $variables = [])
    {
        if ($this->request->getControllerName() == 'email_template' && $this->request->getActionName() == 'preview') {
            $variables['card_image_base_url'] = $this->assetRepo
                    ->getUrl('Dotsquare_Giftcard::images/email/cards') . '/';
        }
        return $proceed($variables);
    }
}
