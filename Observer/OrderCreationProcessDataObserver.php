<?php

namespace Dotsquare\Giftcard\Observer;

use Dotsquare\Giftcard\Api\GiftcardCartManagementInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote;
use Magento\Framework\Escaper;

/**
 * Class OrderCreationProcessDataObserver
 *
 * @package Dotsquare\Giftcard\Observer
 */
class OrderCreationProcessDataObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $giftcardCartManagement;

    /**
     * @var GiftcardCartManagementInterface
     */
    private $messageManager;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param GiftcardCartManagementInterface $giftcardCartManagement
     * @param ManagerInterface $messageManager
     * @param Escaper $escaper
     */
    public function __construct(
        GiftcardCartManagementInterface $giftcardCartManagement,
        ManagerInterface $messageManager,
        Escaper $escaper
    ) {
        $this->giftcardCartManagement = $giftcardCartManagement;
        $this->messageManager = $messageManager;
        $this->escaper = $escaper;
    }

    /**
     * Management Gift Card codes
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getOrderCreateModel()->getQuote();
        $request = $observer->getEvent()->getRequest();

        if (isset($request['ds_giftcard_apply']) && $code = $request['ds_giftcard_apply']) {
            try {
                $this->giftcardCartManagement->set($quote->getId(), $code, false);
                $this->messageManager->addSuccessMessage(
                    __('Gift Card code "%1" was successfully applied', $this->escaper->escapeHtml($code))
                );
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Cannot apply Gift Card code'));
            }
        }

        if (isset($request['ds_giftcard_remove']) && $code = $request['ds_giftcard_remove']) {
            try {
                $this->giftcardCartManagement->remove($quote->getId(), $code, false);
                $this->messageManager->addSuccessMessage(
                    __('Gift Card code "%1" was successfully removed', $this->escaper->escapeHtml($code))
                );
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Cannot remove Gift Card code'));
            }
        }
        return $this;
    }
}
