<?php

namespace Dotsquare\Giftcard\Controller\Adminhtml\Pool\Code;

use Magento\Backend\App\Action\Context;
use Dotsquare\Giftcard\Model\FileUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Backend\App\Action;

/**
 * Class Upload
 *
 * @package Dotsquare\Giftcard\Controller\Adminhtml\Pool\Code
 */
class Upload extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dotsquare_Giftcard::giftcard_pools';

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @param Context $context
     * @param FileUploader $fileUploader
     */
    public function __construct(
        Context $context,
        FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    /**
     * Image uploader action
     *
     * @return Json
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        try {
            $result = $this->fileUploader->saveToTmpFolder('csv_file');
        } catch (\Exception $exception) {
            $result = [
                'error' => $exception->getMessage(),
                'errorcode' => $exception->getCode()
            ];
        }
        return $resultJson->setData($result);
    }
}
