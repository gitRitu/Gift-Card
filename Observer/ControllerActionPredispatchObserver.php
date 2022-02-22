<?php

namespace Dotsquare\Giftcard\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Backend\Model\Session;
use Magento\Framework\App\ActionFlag;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Action\Action;

/**
 * Class ControllerActionPredispatchObserver
 *
 * @package Dotsquare\Giftcard\Observer
 */
class ControllerActionPredispatchObserver implements ObserverInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ActionFlag
     */
    private $actionFlag;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param Session $session
     * @param ActionFlag $actionFlag
     * @param UrlInterface $url
     */
    public function __construct(
        Session $session,
        ActionFlag $actionFlag,
        UrlInterface $url
    ) {
        $this->session = $session;
        $this->actionFlag = $actionFlag;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\Action\Action $controller */
        $controller = $observer->getEvent()->getControllerAction();
        /** @var \Magento\Framework\App\RequestInterface $request */
        $request = $observer->getEvent()->getRequest();
        $actionName = $request->getFullActionName();

        switch ($actionName) {
            case 'catalog_product_index':
                $this->session->setResetBackToDsGiftcardGridFlag(true);
                if ($controller->getRequest()->getParam('menu', false)) {
                    $this->session->setBackToDsGiftcardGridFlag(false);
                } else {
                    if ($this->session->getBackToDsGiftcardGridFlag()) {
                        $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
                        $this->actionFlag->set('', Action::FLAG_NO_POST_DISPATCH, true);

                        $giftcardParams = $this->session->getBackToDsGiftcardParams();
                        $this->session->getBackToDsGiftcardParams(null);
                        if (is_array($giftcardParams) && isset($giftcardParams['dsgcBack'])) {
                            $backUrl = $giftcardParams['dsgcBack'];
                        } else {
                            $backUrl = $this->session->getBackToDsGiftcardParams();
                        }

                        $params = [];
                        if ($backUrl == 'code') {
                            $url = 'ds_giftcard_admin/giftcard/index';
                        } elseif ($backUrl == 'editCode' && isset($giftcardParams['dsgcId'])) {
                            $url = 'ds_giftcard_admin/giftcard/edit';
                            $params = ['id' => $giftcardParams['dsgcId']];
                        } else {
                            $url = 'ds_giftcard_admin/product/index';
                        }

                        $controller->getResponse()->setRedirect($this->url->getUrl($url, $params));
                    }
                }
                break;
            case 'catalog_product_edit':
            case 'catalog_product_new':
                if ($this->session->getResetBackToDsGiftcardGridFlag()) {
                    $this->session->setBackToDsGiftcardGridFlag(false);
                }
                $dsGcParams = [];
                foreach ($request->getParams() as $key => $value) {
                    $result = strpos($key, 'dsgc');
                    if ($result === 0) {
                        $dsGcParams[$key] = $value;
                    }
                }
                $this->session->setBackToDsGiftcardParams($dsGcParams);
                break;
        }
        return $this;
    }
}
