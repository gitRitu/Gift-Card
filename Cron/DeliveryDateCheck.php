<?php

namespace Dotsquare\Giftcard\Cron;

use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Dotsquare\Giftcard\Model\Flag;
use Dotsquare\Giftcard\Model\Source\EmailStatus;
use Dotsquare\Giftcard\Model\Source\Entity\Attribute\GiftcardType;
use Dotsquare\Giftcard\Model\Source\Giftcard\EmailTemplate;
use Dotsquare\Giftcard\Model\Source\Giftcard\Status;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;

/**
 * Class DeliveryDateCheck
 *
 * @package Dotsquare\Giftcard\Cron
 */
class DeliveryDateCheck extends CronAbstract
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->isLocked(Flag::DS_GC_DELIVERY_DATE_CHECK_LAST_EXEC_TIME)) {
            return $this;
        }
        $this->processSend();
        $this->setFlagData(Flag::DS_GC_DELIVERY_DATE_CHECK_LAST_EXEC_TIME);
    }

    /**
     * Send Gift Card codes by delivery date
     *
     * @return $this
     */
    private function processSend()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $currentDateTime = $now->format(StdlibDateTime::DATETIME_PHP_FORMAT);
        $this->searchCriteriaBuilder
            ->addFilter(GiftcardInterface::STATE, Status::ACTIVE)
            ->addFilter(GiftcardInterface::EMAIL_TEMPLATE, EmailTemplate::DO_NOT_SEND, 'neq')
            ->addFilter(GiftcardInterface::TYPE, [GiftcardType::VALUE_COMBINED, GiftcardType::VALUE_VIRTUAL], 'in')
            ->addFilter(GiftcardInterface::EMAIL_SENT, [EmailStatus::AWAITING, EmailStatus::FAILED], 'in')
            ->addFilter(GiftcardInterface::DELIVERY_DATE, $currentDateTime, 'checkDeliveryDate');

        $sendNowGiftcards = $this->giftcardRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();

        $giftcards = [];
        foreach ($sendNowGiftcards as $sendNowGiftcard) {
            $giftcards[] = $sendNowGiftcard->getCode();
        }
        $this->giftcardManagement->sendGiftcardByCode($giftcards);
        return $this;
    }
}
