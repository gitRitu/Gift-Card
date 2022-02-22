<?php

namespace Dotsquare\Giftcard\Plugin\Model\Order;

use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Api\Data\CreditmemoExtensionFactory;
use Magento\Sales\Api\Data\CreditmemoSearchResultInterface;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Creditmemo\CollectionFactory
    as GiftcardCreditmemoCollectionFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\CreditmemoInterface as GiftcardCreditmemoInterface;
use Magento\Framework\EntityManager\EntityManager;

/**
 * Class CreditmemoRepositoryPlugin
 *
 * @package Dotsquare\Giftcard\Plugin\Model\Order
 */
class CreditmemoRepositoryPlugin
{
    /**
     * @var CreditmemoExtensionFactory
     */
    private $creditmemoExtensionFactory;

    /**
     * @var GiftcardCreditmemoCollectionFactory
     */
    private $giftcardCreditmemoCollectionFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param CreditmemoExtensionFactory $creditmemoExtensionFactory
     * @param GiftcardCreditmemoCollectionFactory $giftcardCreditmemoCollectionFactory
     * @param EntityManager $entityManager
     */
    public function __construct(
        CreditmemoExtensionFactory $creditmemoExtensionFactory,
        GiftcardCreditmemoCollectionFactory $giftcardCreditmemoCollectionFactory,
        EntityManager $entityManager
    ) {
        $this->creditmemoExtensionFactory = $creditmemoExtensionFactory;
        $this->giftcardCreditmemoCollectionFactory = $giftcardCreditmemoCollectionFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Save Gift Card codes
     *
     * @param CreditmemoRepositoryInterface $object
     * @param CreditmemoInterface $creditmemo
     * @return CreditmemoInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(CreditmemoRepositoryInterface $object, CreditmemoInterface $creditmemo)
    {
        if ($creditmemo->getExtensionAttributes() && $creditmemo->getExtensionAttributes()->getDsGiftcardCodes()) {
            $giftcards = $creditmemo->getExtensionAttributes()->getDsGiftcardCodes();
            /** @var GiftcardCreditmemoInterface $giftcard */
            foreach ($giftcards as $giftcard) {
                $giftcard->setCreditmemoId($creditmemo->getEntityId());
                $this->entityManager->save($giftcard);
            }
        }

        return $creditmemo;
    }

    /**
     * Add Gift Card codes to creditmemo object
     *
     * @param CreditmemoRepositoryInterface $subject
     * @param CreditmemoInterface $creditmemo
     * @return CreditmemoInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(CreditmemoRepositoryInterface $subject, CreditmemoInterface $creditmemo)
    {
        return $this->addGiftcardDataToCreditmemo($creditmemo);
    }

    /**
     * Add Gift Card data to order object
     *
     * @param CreditmemoRepositoryInterface $subject
     * @param CreditmemoSearchResultInterface $creditmemos
     * @return CreditmemoInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(CreditmemoRepositoryInterface $subject, CreditmemoSearchResultInterface $creditmemos)
    {
        /** @var CreditmemoInterface $order */
        foreach ($creditmemos->getItems() as $creditmemo) {
            $this->addGiftcardDataToCreditmemo($creditmemo);
        }
        return $creditmemos;
    }

    /**
     * Add Gift Card data to creditmemo
     *
     * @param CreditmemoInterface $creditmemo
     * @return CreditmemoInterface
     */
    public function addGiftcardDataToCreditmemo($creditmemo)
    {
        if ($creditmemo->getExtensionAttributes() && $creditmemo->getExtensionAttributes()->getDsGiftcardCodes()) {
            return $creditmemo;
        }

        $giftcardCreditmemoItems = $this->giftcardCreditmemoCollectionFactory->create()
            ->addFieldToFilter('creditmemo_id', $creditmemo->getEntityId())
            ->load()
            ->getItems();

        if (!$giftcardCreditmemoItems) {
            return $creditmemo;
        }

        /** @var \Magento\Sales\Api\Data\CreditmemoExtension $creditmemoExtension */
        $creditmemoExtension = $creditmemo->getExtensionAttributes()
            ? $creditmemo->getExtensionAttributes()
            : $this->creditmemoExtensionFactory->create();

        $creditmemoExtension->setBaseDsGiftcardAmount($creditmemo->getBaseDsGiftcardAmount());
        $creditmemoExtension->setDsGiftcardAmount($creditmemo->getDsGiftcardAmount());
        $creditmemoExtension->setDsGiftcardCodes($giftcardCreditmemoItems);
        $creditmemo->setExtensionAttributes($creditmemoExtension);
        return $creditmemo;
    }
}
