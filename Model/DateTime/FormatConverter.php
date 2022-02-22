<?php

namespace Dotsquare\Giftcard\Model\DateTime;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class FormatConverter
 * @package Dotsquare\Giftcard\Model\DateTime
 */
class FormatConverter
{
    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @param TimezoneInterface $localeDate
     */
    public function __construct(TimezoneInterface $localeDate)
    {
        $this->localeDate = $localeDate;
    }

    /**
     * Converts PHP IntlFormatter format to Js Calendar format
     *
     * @param string $format
     * @return string
     */
    public function convertToJsCalendarFormat($format = null)
    {
        $format = $format ? : $this->getDateFormat();
        $format = preg_replace('/d+/i', 'dd', $format);
        $format = preg_replace('/m+/i', 'mm', $format);
        $format = preg_replace('/y+/i', 'yyyy', $format);
        $format = preg_replace('/\s+\S+/', '', $format);

        return $format;
    }

    /**
     * Converts PHP IntlFormatter format to moment Js format
     *
     * @param string $format
     * @return string
     */
    public function convertToMomentJsFormat($format = null)
    {
        $format = $format ? : $this->getDateFormat();
        $format = preg_replace('/d+/i', 'DD', $format);
        $format = preg_replace('/m+/i', 'MM', $format);
        $format = preg_replace('/y+/i', 'YYYY', $format);
        $format = preg_replace('/\s+\S+/', '', $format);

        return $format;
    }

    /**
     * Retrieve short date format
     *
     * @return string
     */
    private function getDateFormat()
    {
        return $this->localeDate->getDateFormat();
    }
}
