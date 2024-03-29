<?php

namespace Dotsquare\Giftcard\Plugin\Model\Order;

use Dotsquare\Giftcard\Api\Data\Giftcard\HistoryActionInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\HistoryActionInterfaceFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\History\EntityInterface as HistoryEntityInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\History\EntityInterfaceFactory as HistoryEntityInterfaceFactory;
use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Dotsquare\Giftcard\Api\Data\OptionInterface;
use Dotsquare\Giftcard\Api\Data\ProductAttributeInterface;
use Dotsquare\Giftcard\Api\GiftcardRepositoryInterface;
use Dotsquare\Giftcard\Model\Config;
use Dotsquare\Giftcard\Model\Product\Option;
use Dotsquare\Giftcard\Model\Product\Type\Giftcard as ProductGiftcard;
use Dotsquare\Giftcard\Api\Data\GiftcardInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\DataObjectHelper;
use Dotsquare\Giftcard\Api\Data\OptionInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\EntityManager\EntityManager;
use Dotsquare\Giftcard\Api\Data\Giftcard\InvoiceInterface as GiftcardInvoiceInterface;
use Magento\Sales\Model\Order\Invoice;
use Dotsquare\Giftcard\Model\Statistics;
use Magento\Sales\Model\Order\Invoice\Item;
use Dotsquare\Giftcard\Api\PoolManagementInterface;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard as ResourceGiftcard;
use Dotsquare\Giftcard\Model\Source\History\EntityType as SourceHistoryEntityType;
use Dotsquare\Giftcard\Model\Source\History\Comment\Action as SourceHistoryCommentAction;

/**
 * Class InvoicePlugin
 *
 * @package Dotsquare\Giftcard\Plugin\Model\Order
 */
class InvoicePlugin
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var GiftcardInterfaceFactory
     */
    private $giftcardDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var OptionInterfaceFactory
     */
    private $optionFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var InvoiceRepositoryPlugin
     */
    private $invoiceRepositoryPlugin;

    /**
     * @var Statistics
     */
    private $statistics;

    /**
     * @var PoolManagementInterface
     */
    private $poolManagement;

    /**
     * @var ResourceGiftcard
     */
    private $resourceGiftcard;

    /**
     * @var HistoryActionInterfaceFactory
     */
    private $historyActionFactory;

    /**
     * @var HistoryEntityInterfaceFactory
     */
    private $historyEntityFactory;

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param GiftcardInterfaceFactory $giftcardDataFactory
     * @param OptionInterfaceFactory $optionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param LoggerInterface $logger
     * @param EntityManager $entityManager
     * @param InvoiceRepositoryPlugin $invoiceRepositoryPlugin
     * @param Statistics $statistics
     * @param PoolManagementInterface $poolManagement
     * @param ResourceGiftcard $resourceGiftcard
     * @param HistoryActionInterfaceFactory $historyActionFactory
     * @param HistoryEntityInterfaceFactory $historyEntityFactory
     * @param TimezoneInterface $localeDate
     */
    public function __construct(
        GiftcardRepositoryInterface $giftcardRepository,
        GiftcardInterfaceFactory $giftcardDataFactory,
        OptionInterfaceFactory $optionFactory,
        DataObjectHelper $dataObjectHelper,
        LoggerInterface $logger,
        EntityManager $entityManager,
        InvoiceRepositoryPlugin $invoiceRepositoryPlugin,
        Statistics $statistics,
        PoolManagementInterface $poolManagement,
        ResourceGiftcard $resourceGiftcard,
        HistoryActionInterfaceFactory $historyActionFactory,
        HistoryEntityInterfaceFactory $historyEntityFactory,
        TimezoneInterface $localeDate
    ) {
        $this->giftcardRepository = $giftcardRepository;
        $this->giftcardDataFactory = $giftcardDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->optionFactory = $optionFactory;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->invoiceRepositoryPlugin = $invoiceRepositoryPlugin;
        $this->statistics = $statistics;
        $this->poolManagement = $poolManagement;
        $this->resourceGiftcard = $resourceGiftcard;
        $this->historyActionFactory = $historyActionFactory;
        $this->historyEntityFactory = $historyEntityFactory;
        $this->localeDate = $localeDate;
    }

    /**
     * Add Gift Card data to invoice object
     *
     * @param Invoice $subject
     * @param Invoice $invoice
     * @return Invoice
     */
    public function afterAddData($subject, $invoice)
    {
        return $this->invoiceRepositoryPlugin->addGiftcardDataToInvoice($invoice);
    }

    /**
     * Generate Gift Card codes after save invoice
     *
     * @param Invoice $object
     * @param Invoice $invoice
     * @return Invoice
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(Invoice $object, Invoice $invoice)
    {
        $this->saveGiftcardCodes($invoice);
        $this->saveGiftcardProduct($invoice);

        return $invoice;
    }

    /**
     * Save Gift Card codes
     *
     * @param Invoice $invoice
     * @return void
     */
    private function saveGiftcardCodes($invoice)
    {
        if ($invoice->getExtensionAttributes() && $invoice->getExtensionAttributes()->getDsGiftcardCodes()) {
            $giftcards = $invoice->getExtensionAttributes()->getDsGiftcardCodes();
            /** @var GiftcardInvoiceInterface $giftcard */
            foreach ($giftcards as $giftcard) {
                $giftcard->setInvoiceId($invoice->getEntityId());
                $this->entityManager->save($giftcard);
            }
        }
    }

    /**
     * Save Gift Card product
     *
     * @param Invoice $invoice
     * @return void
     * @throws LocalizedException
     */
    private function saveGiftcardProduct($invoice)
    {
        if (!$invoice->wasPayCalled()) {
            return;
        }

        foreach ($invoice->getAllItems() as $item) {
            /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
            if ($item->getOrderItem()->getProductType() != ProductGiftcard::TYPE_CODE) {
                continue;
            }
            /** @var Option $options */
            $options = $this->getProductOptions($item->getOrderItem()->getProductOptions());
            $qty = (int)$item->getQty();
            if ($options->getDsGcCreatedCodes()) {
                $qty -= count($options->getDsGcCreatedCodes());
            }
            if (!$qty) {
                continue;
            }
            $giftcardCodesByProduct = [];
            while ($qty > 0) {
                try {
                    $this->resourceGiftcard->beginTransaction();
                    $historyObject = $this->createHistoryObject(
                        $invoice,
                        SourceHistoryCommentAction::CREATED_BY_ORDER
                    );
                    /** @var GiftcardInterface $giftcardObject */
                    $giftcardObject = $this->giftcardDataFactory->create();
                    $giftcardObject
                        ->setOrderId($invoice->getOrder()->getId())
                        ->setProductId($item->getOrderItem()->getProductId())
                        ->setCode($this->getGiftcardCode($item->getOrderItem()->getProduct()))
                        ->setType(
                            $item->getOrderItem()->getProduct()->getData(ProductAttributeInterface::CODE_DS_GC_TYPE)
                        )->setInitialBalance($item->getBasePrice())
                        ->setWebsiteId($invoice->getStore()->getWebsiteId())
                        ->setSenderName($options->getDsGcSenderName())
                        ->setSenderEmail($options->getDsGcSenderEmail())
                        ->setRecipientName($options->getDsGcRecipientName())
                        ->setRecipientEmail($options->getDsGcRecipientEmail())
                        ->setEmailTemplate($options->getDsGcTemplate())
                        ->setHeadline($options->getDsGcHeadline())
                        ->setMessage($options->getDsGcMessage())
                        ->setCurrentHistoryAction($historyObject);

                    $expireAt = new \DateTime();
                    $expireAt->setTime(0, 0, 0);
                    if ($options->getDsGcDeliveryDate()) {
                        $deliverydate = $this->getDeliveryDate($options->getDsGcDeliveryDate(), $invoice);
                        $deliverydate
                            ->setTimezone(new \DateTimeZone($options->getDsGcDeliveryDateTimezone()))
                            ->setTimezone(new \DateTimeZone('UTC'));
                        $giftcardObject
                            ->setDeliveryDate($deliverydate->format(StdlibDateTime::DATETIME_PHP_FORMAT))
                            ->setDeliveryDateTimezone($options->getDsGcDeliveryDateTimezone());

                        if ($expireAt < $deliverydate) {
                            $expireAt = $deliverydate;
                        }
                    }

                    $expireAfter = $item->getOrderItem()->getProduct()->getData(
                        ProductAttributeInterface::CODE_DS_GC_EXPIRE
                    );
                    if ($expireAfter) {
                        $expireAt->add(new \DateInterval('P' . $expireAfter . 'D'));
                        $giftcardObject->setExpireAt($expireAt->format(StdlibDateTime::DATETIME_PHP_FORMAT));
                    }
                    $giftcardCode = $this->giftcardRepository->save($giftcardObject);
                    $this->resourceGiftcard->commit();

                    $giftcardCodesByProduct[] = $giftcardCode->getCode();
                    $qty--;
                } catch (\Exception $e) {
                    $this->resourceGiftcard->rollBack();
                    $this->logger->critical($e);
                    throw new LocalizedException(__($e->getMessage()));
                }
            }
            $giftcardCodesByProduct = $options->getDsGcCreatedCodes()
                ? array_merge($options->getDsGcCreatedCodes(), $giftcardCodesByProduct)
                : $giftcardCodesByProduct;
            $options->setDsGcCreatedCodes($giftcardCodesByProduct);
            $options = $options->getData();
            $info = $item->getOrderItem()->getProductOptionByCode('info_buyRequest');
            if ($info) {
                $options['info_buyRequest'] = $info;
            }
            $item->getOrderItem()
                ->setProductOptions($options)
                ->save();

            $this->statistics->updateStatistics(
                $item->getOrderItem()->getProductId(),
                $invoice->getStoreId(),
                $this->getPurchasedStatData($item)
            );
        }
    }

    /**
     * Retrieve Gift Card code from pool or null
     *
     * @param Product $product
     * @return string|null
     */
    private function getGiftcardCode($product)
    {
        $codePoolId = $product->getData(ProductAttributeInterface::CODE_DS_GC_POOL);

        return $codePoolId
            ? $this->poolManagement->pullCodeFromPool($codePoolId)
            : null ;
    }

    /**
     * Retrieve Ds Gift Card product options
     *
     * @param [] $options
     * @return OptionInterface
     */
    private function getProductOptions($options)
    {
        /** @var OptionInterface $optionObject */
        $optionObject = $this->optionFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $optionObject,
            $options,
            OptionInterface::class
        );
        return $optionObject;
    }

    /**
     * @param Item $item
     * @return []
     */
    private function getPurchasedStatData(Item $item)
    {
        return [
            'purchased_qty' => $item->getQty(),
            'purchased_amount' => $item->getBaseRowTotal()
        ];
    }

    /**
     * Create History object
     *
     * @param Invoice $invoice
     * @param int $status
     * @return HistoryActionInterface
     */
    private function createHistoryObject($invoice, $status)
    {
        /** @var HistoryEntityInterface $orderHistoryEntityObject */
        $orderHistoryEntityObject = $this->historyEntityFactory->create();
        $orderHistoryEntityObject
            ->setEntityType(SourceHistoryEntityType::ORDER_ID)
            ->setEntityId($invoice->getOrder()->getEntityId())
            ->setEntityLabel($invoice->getOrder()->getIncrementId());

        /** @var HistoryActionInterface $historyObject */
        $historyObject = $this->historyActionFactory->create();
        $historyObject
            ->setActionType($status)
            ->setEntities([$orderHistoryEntityObject]);
        return $historyObject;
    }

    /**
     * Retrieve delivery date
     *
     * @param string $deliveryDate
     * @param Invoice $invoice
     * @return \DateTime
     * @throws \Exception
     */
    private function getDeliveryDate($deliveryDate, $invoice)
    {
        $locale = $invoice->getStore()->getConfig(Config::XML_PATH_GENERAL_LOCALE_CODE);
        $timezone = $invoice->getStore()->getConfig(Config::XML_PATH_GENERAL_LOCALE_TIMEZONE);
        $deliveryDate = $this->localeDate->date($deliveryDate, $locale)->format(StdlibDateTime::DATETIME_PHP_FORMAT);

        return new \DateTime($deliveryDate, new \DateTimeZone($timezone));
    }
}
