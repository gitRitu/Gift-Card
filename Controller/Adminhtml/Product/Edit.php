<?php

namespace Dotsquare\Giftcard\Controller\Adminhtml\Product;

/**
 * Class Edit
 *
 * @package Dotsquare\Giftcard\Controller\Adminhtml\Product
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dotsquare_Giftcard::giftcard_products';

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_getSession()->setBackToDsGiftcardGridFlag(true);
        $this->_getSession()->setResetBackToDsGiftcardGridFlag(false);

        $dsGcParams = [];
        foreach ($this->getRequest()->getParams() as $key => $value) {
            $result = strpos($key, 'dsgc');
            if ($result === 0) {
                $dsGcParams[$key] = $value;
            }
        }
        return $this->_redirect(
            'catalog/product/edit',
            array_merge(
                [
                    'id' => $this->getRequest()->getParam('id'),
                    'store' => $this->getRequest()->getParam('store')
                ],
                $dsGcParams
            )
        );
    }
}
