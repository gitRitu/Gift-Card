<?php

namespace Dotsquare\Giftcard\Model;

use Magento\Framework\Flag as FrameworkFlag;

/**
 * Class Flag
 *
 * @package Dotsquare\Giftcard\Model
 */
class Flag extends FrameworkFlag
{
    /**#@+
     * Constants for Gift Card cron flags
     */
    const DS_GC_EXPIRATION_CHECK_LAST_EXEC_TIME = 'ds_gc_expiration_check_last_exec_time';
    const DS_GC_DELIVERY_DATE_CHECK_LAST_EXEC_TIME = 'ds_gc_delivery_date_check_last_exec_time';
    const DS_GC_EXPIRATION_REMINDER_LAST_EXEC_TIME = 'ds_gc_expiration_reminder_last_exec_time';
    /**#@-*/

    /**
     * Setter for flag code
     * @codeCoverageIgnore
     *
     * @param string $code
     * @return $this
     */
    public function setGiftcardFlagCode($code)
    {
        $this->_flagCode = $code;
        return $this;
    }
}
