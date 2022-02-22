<?php

namespace Dotsquare\Giftcard\Plugin\Model\Order;

use Magento\Sales\Model\Order\Creditmemo;

/**
 * Class CreditmemoPlugin
 *
 * @package Dotsquare\Giftcard\Plugin\Model\Order
 */
class CreditmemoPlugin
{
    /**
     * @var CreditmemoRepositoryPlugin
     */
    private $creditmemoRepositoryPlugin;

    /**
     * @param CreditmemoRepositoryPlugin $creditmemoRepositoryPlugin
     */
    public function __construct(
        CreditmemoRepositoryPlugin $creditmemoRepositoryPlugin
    ) {
        $this->creditmemoRepositoryPlugin = $creditmemoRepositoryPlugin;
    }

    /**
     * Add Gift Card data to credit memo object
     *
     * @param Creditmemo $subject
     * @param Creditmemo $creditmemo
     * @return Creditmemo
     */
    public function afterAddData($subject, $creditmemo)
    {
        return $this->creditmemoRepositoryPlugin->addGiftcardDataToCreditmemo($creditmemo);
    }

    /**
     * Set allowZeroGrandTotal flag
     *
     * @param Creditmemo $creditmemo
     * @return void
     */
    public function beforeIsValidGrandTotal($creditmemo)
    {
        if ($creditmemo->getExtensionAttributes() && $creditmemo->getExtensionAttributes()->getDsGiftcardCodes()) {
            $creditmemo->setAllowZeroGrandTotal(true);
        }
    }
}
