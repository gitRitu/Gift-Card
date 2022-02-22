<?php

namespace Dotsquare\Giftcard\Controller\Adminhtml\Giftcard;

use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Dotsquare\Giftcard\Api\Data\GiftcardInterfaceFactory;
use Dotsquare\Giftcard\Api\GiftcardManagementInterface;
use Dotsquare\Giftcard\Api\GiftcardRepositoryInterface;
use Dotsquare\Giftcard\Model\Config;
use Dotsquare\Giftcard\Model\Source\EmailStatus;
use Dotsquare\Giftcard\Model\Source\Giftcard\EmailTemplate;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Dotsquare\Giftcard\Api\PoolManagementInterface;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard as ResourceGiftcard;

/**
 * Class Save
 *
 * @package Dotsquare\Giftcard\Controller\Adminhtml\Giftcard
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dotsquare_Giftcard::giftcard_codes';

    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var GiftcardManagementInterface
     */
    private $giftcardManagement;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var GiftcardInterfaceFactory
     */
    private $giftcardDataFactory;

    /**
     * @var PoolManagementInterface
     */
    private $poolManagement;

    /**
     * @var ResourceGiftcard
     */
    private $resourceGiftcard;

    /**
     * @param Context $context
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param GiftcardManagementInterface $giftcardManagement
     * @param Config $config
     * @param DateTime $dateTime
     * @param TimezoneInterface $localeDate
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param GiftcardInterfaceFactory $giftcardDataFactory
     * @param PoolManagementInterface $poolManagement
     * @param ResourceGiftcard $resourceGiftcard
     */
    public function __construct(
        Context $context,
        GiftcardRepositoryInterface $giftcardRepository,
        GiftcardManagementInterface $giftcardManagement,
        Config $config,
        DateTime $dateTime,
        TimezoneInterface $localeDate,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        GiftcardInterfaceFactory $giftcardDataFactory,
        PoolManagementInterface $poolManagement,
        ResourceGiftcard $resourceGiftcard
    ) {
        parent::__construct($context);
        $this->giftcardRepository = $giftcardRepository;
        $this->giftcardManagement = $giftcardManagement;
        $this->config = $config;
        $this->dateTime = $dateTime;
        $this->localeDate = $localeDate;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
        $this->giftcardDataFactory = $giftcardDataFactory;
        $this->poolManagement = $poolManagement;
        $this->resourceGiftcard = $resourceGiftcard;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            try {
                $this->resourceGiftcard->beginTransaction();
                $data = $this->prepareData($data);
                $giftcard = $this->performSave($data);

                $this->dataPersistor->clear('ds_giftcard_giftcard');
                $this->messageManager->addSuccessMessage(__('Gift Card Code was successfully saved'));

                $this->resourceGiftcard->commit();
                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $giftcard->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Gift Card code')
                );
            }
            $this->resourceGiftcard->rollBack();
            $this->dataPersistor->set('ds_giftcard_giftcard', $data);
            $id = isset($data['id']) ? $data['id'] : false;
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $id, '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/new', ['_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Prepare data before save
     *
     * @param [] $data
     * @return []
     */
    private function prepareData($data)
    {
        if ($data['id'] && isset($data['expire_at']) && $data['expire_at']) {
            $data['expire_at'] = $this->dateTime->gmtDate(
                StdlibDateTime::DATETIME_PHP_FORMAT,
                $data['expire_at']
            );
        }
        if (!$data['id']) {
            $expireAfter = null;
            $codeFromPool = true;
            if (isset($data['use_default'])) {
                if (isset($data['use_default']['expire_after']) && (bool)$data['use_default']['expire_after']) {
                    $expireAfter = $this->config->getGiftcardExpireDays();
                }
                if (isset($data['use_default']['code_pool']) && (bool)$data['use_default']['code_pool']) {
                    $codeFromPool = false;
                }
            }
            if (null === $expireAfter && isset($data['expire_after'])) {
                $expireAfter = $data['expire_after'];
            }
            if ($expireAfter) {
                $data['expire_at'] = $this->localeDate
                    ->date('+' . $expireAfter . 'days', null, false, false)
                    ->format(StdlibDateTime::DATETIME_PHP_FORMAT);
            }
            if ($codeFromPool) {
                $data['code'] = $this->poolManagement->pullCodeFromPool($data['code_pool']);
            }
        }

        if (isset($data['delivery_date']) && $data['delivery_date']) {
            $deliveryDate = $this->localeDate->date($data['delivery_date']);
            $deliveryDate->setTimezone(new \DateTimeZone('UTC'));
            $data['delivery_date'] = $deliveryDate->format(StdlibDateTime::DATETIME_PHP_FORMAT);
        } else {
            $data['delivery_date_timezone'] = null;
        }

        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }
        return $data;
    }

    /**
     * Perform save
     *
     * @param [] $data
     * @return GiftcardInterface
     */
    private function performSave($data)
    {
        $saveAction = $this->getRequest()->getParam('action');
        $id = isset($data['id']) ? $data['id'] : false;
        $dataObject = $id
            ? $this->giftcardRepository->get($id)
            : $this->giftcardDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $dataObject,
            $data,
            GiftcardInterface::class
        );
        if (!$dataObject->getId()) {
            $dataObject->setId(null);
        }
        $giftcard = $this->giftcardRepository->save($dataObject);
        if ($saveAction == 'save_and_send' && $giftcard->getEmailTemplate() != EmailTemplate::DO_NOT_SEND) {
            $giftcards = $this->giftcardManagement->sendGiftcardByCode($giftcard->getCode(), false);
            $giftcard = count($giftcards) ? array_shift($giftcards) : null;
            if ($giftcard && $giftcard->getEmailSent() == EmailStatus::SENT) {
                $this->messageManager->addSuccessMessage(__('Email was successfully sent'));
            } else {
                $this->messageManager->addErrorMessage(__('Could not send email'));
            }
        }
        return $giftcard;
    }
}
