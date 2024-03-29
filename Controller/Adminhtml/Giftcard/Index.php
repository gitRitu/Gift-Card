<?php

namespace Dotsquare\Giftcard\Controller\Adminhtml\Giftcard;

use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;

/**
 * Class Index
 *
 * @package Dotsquare\Giftcard\Controller\Adminhtml\Giftcard
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dotsquare_Giftcard::giftcard_codes';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dotsquare_Giftcard::giftcard_codes');
        $resultPage->getConfig()->getTitle()->prepend(__('Gift Card Codes'));
        return $resultPage;
    }
}
