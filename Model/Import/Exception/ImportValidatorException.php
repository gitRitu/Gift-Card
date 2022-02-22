<?php

namespace Dotsquare\Giftcard\Model\Import\Exception;

use Dotsquare\Giftcard\Api\Exception\ImportValidatorExceptionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ImportValidatorException
 *
 * @package Dotsquare\Giftcard\Model\Import\Exception
 */
class ImportValidatorException extends LocalizedException implements ImportValidatorExceptionInterface
{
}
