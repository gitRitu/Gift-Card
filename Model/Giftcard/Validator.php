<?php

namespace Dotsquare\Giftcard\Model\Giftcard;

use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Magento\Framework\Validator\AbstractValidator;
use Dotsquare\Giftcard\Model\Source\Giftcard\Status;

/**
 * Class Validator
 *
 * @package Dotsquare\Giftcard\Model\Giftcard
 */
class Validator extends AbstractValidator
{
    /**
     * Returns true if and only Gift Card is valid for processing
     *
     * @param GiftcardInterface $giftcard
     * @return bool
     */
    public function isValid($giftcard)
    {
        $this->_clearMessages();

        if ($giftcard->getState() == Status::DEACTIVATED) {
            $this->_addMessages([__('The specified Gift Card code deactivated')]);
        }
        if ($giftcard->getState() == Status::EXPIRED) {
            $this->_addMessages([__('The specified Gift Card code expired')]);
        }
        if ($giftcard->getState() == Status::USED) {
            $this->_addMessages([__('The specified Gift Card code used')]);
        }

        return empty($this->getMessages());
    }
}
