<?php

namespace Dotsquare\Giftcard\Plugin\CustomerData;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\CustomerData\Customer;

/**
 * Class CustomerPlugin
 *
 * @package Dotsquare\Giftcard\Plugin\CustomerData
 */
class CustomerPlugin
{
    /**
     * @var CurrentCustomer
     */
    private $currentCustomer;

    /**
     * @param CurrentCustomer $currentCustomer
     */
    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * @param Customer $subject
     * @param string[] $result
     * @return string[]
     */
    public function afterGetSectionData($subject, $result)
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return $result;
        }
        $customer = $this->currentCustomer->getCustomer();
        $result['email'] = $customer->getEmail();

        return $result;
    }
}
