<?php

namespace Dotsquare\Giftcard\Plugin\Model;

use Magento\Sales\Model\Order;
use Dotsquare\Giftcard\Plugin\Model\Order\OrderRepositoryPlugin;

/**
 * Class OrderPlugin
 *
 * @package Dotsquare\Giftcard\Plugin\Model
 */
class OrderPlugin
{
    /**
     * @var OrderRepositoryPlugin
     */
    private $orderRepositoryPlugin;

    /**
     * @param OrderRepositoryPlugin $orderRepositoryPlugin
     */
    public function __construct(
        OrderRepositoryPlugin $orderRepositoryPlugin
    ) {
        $this->orderRepositoryPlugin = $orderRepositoryPlugin;
    }

    /**
     * Add Gift Card data to order object
     *
     * @param Order $subject
     * @param Order $order
     * @return Order
     */
    public function afterLoad($subject, $order)
    {
        return $this->orderRepositoryPlugin->addGiftcardDataToOrder($order);
    }

    /**
     * Set forced canCreditmemo flag
     *
     * @param Order $order
     * @return void
     */
    public function beforeCanCreditmemo($order)
    {
        if (!$order->canUnhold() && !$order->isCanceled() && $order->getState() != Order::STATE_CLOSED
            && $order->getDsGiftcardInvoiced() - $order->getDsGiftcardRefunded() > 0
        ) {
            $order->setForcedCanCreditmemo(true);
        }
    }
}
