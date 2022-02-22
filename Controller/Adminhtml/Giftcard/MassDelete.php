<?php

namespace Dotsquare\Giftcard\Controller\Adminhtml\Giftcard;

/**
 * Class MassDelete
 *
 * @package Dotsquare\Giftcard\Controller\Adminhtml\Giftcard
 */
class MassDelete extends \Dotsquare\Giftcard\Controller\Adminhtml\Giftcard\MassAbstract
{
    /**
     * {@inheritdoc}
     */
    protected function massAction($collection)
    {
        $count = 0;
        foreach ($collection->getItems() as $item) {
            $this->giftcardRepository->deleteById($item->getId());
            $count++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 gift card(s) have been deleted.', $count));
    }
}
