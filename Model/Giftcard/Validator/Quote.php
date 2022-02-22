<?php

namespace Dotsquare\Giftcard\Model\Giftcard\Validator;

use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Dotsquare\Giftcard\Api\GiftcardRepositoryInterface;
use Dotsquare\Giftcard\Model\Giftcard\Validator as GiftcardValidator;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Quote
 *
 * @package Dotsquare\Giftcard\Model\Giftcard\Validator
 */
class Quote
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var GiftcardValidator
     */
    private $giftcardValidator;

    /**
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param GiftcardValidator $giftcardValidator
     */
    public function __construct(
        GiftcardRepositoryInterface $giftcardRepository,
        GiftcardValidator $giftcardValidator
    ) {
        $this->giftcardRepository = $giftcardRepository;
        $this->giftcardValidator = $giftcardValidator;
    }

    /**
     * Check if specified gift card code is still valid
     *
     * @param string $giftcardCode
     * @param int $websiteId
     * @return bool
     */
    public function isValid($giftcardCode, $websiteId)
    {
        $isValid = true;
        try {
            $giftcard = $this->giftcardRepository->getByCode($giftcardCode, $websiteId);
            if (!$this->giftcardValidator->isValid($giftcard)) {
                $isValid = false;
            }
        } catch (LocalizedException $exception) {
            $isValid = false;
        }
        return $isValid;
    }
}
