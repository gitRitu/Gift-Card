<?php

namespace Dotsquare\Giftcard\Plugin\Model\Order;

use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Api\Data\InvoiceSearchResultInterface;
use Dotsquare\Giftcard\Model\ResourceModel\Giftcard\Invoice\CollectionFactory as GiftcardInvoiceCollectionFactory;

/**
 * Class InvoiceRepositoryPlugin
 *
 * @package Dotsquare\Giftcard\Plugin\Model\Order
 */
class InvoiceRepositoryPlugin
{
    /**
     * @var InvoiceExtensionFactory
     */
    private $invoiceExtensionFactory;

    /**
     * @var GiftcardInvoiceCollectionFactory
     */
    private $giftcardInvoiceCollectionFactory;

    /**
     * @param InvoiceExtensionFactory $invoiceExtensionFactory
     * @param GiftcardInvoiceCollectionFactory $giftcardInvoiceCollectionFactory
     */
    public function __construct(
        InvoiceExtensionFactory $invoiceExtensionFactory,
        GiftcardInvoiceCollectionFactory $giftcardInvoiceCollectionFactory
    ) {
        $this->invoiceExtensionFactory = $invoiceExtensionFactory;
        $this->giftcardInvoiceCollectionFactory = $giftcardInvoiceCollectionFactory;
    }

    /**
     * Add Gift Card codes to invoice object
     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(InvoiceRepositoryInterface $subject, InvoiceInterface $invoice)
    {
        return $this->addGiftcardDataToInvoice($invoice);
    }

    /**
     * Add Gift Card data to order object
     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceSearchResultInterface $invoices
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(InvoiceRepositoryInterface $subject, InvoiceSearchResultInterface $invoices)
    {
        /** @var InvoiceInterface $order */
        foreach ($invoices->getItems() as $invoice) {
            $this->addGiftcardDataToInvoice($invoice);
        }
        return $invoices;
    }

    /**
     * Add Gift Card data to invoice
     *
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     */
    public function addGiftcardDataToInvoice($invoice)
    {
        if ($invoice->getExtensionAttributes() && $invoice->getExtensionAttributes()->getDsGiftcardCodes()) {
            return $invoice;
        }

        $giftcardInvoiceItems = $this->giftcardInvoiceCollectionFactory->create()
            ->addFieldToFilter('invoice_id', $invoice->getEntityId())
            ->load()
            ->getItems();

        if (!$giftcardInvoiceItems) {
            return $invoice;
        }

        /** @var \Magento\Sales\Api\Data\InvoiceExtension $invoiceExtension */
        $invoiceExtension = $invoice->getExtensionAttributes()
            ? $invoice->getExtensionAttributes()
            : $this->invoiceExtensionFactory->create();

        $invoiceExtension->setBaseDsGiftcardAmount($invoice->getBaseDsGiftcardAmount());
        $invoiceExtension->setDsGiftcardAmount($invoice->getDsGiftcardAmount());
        $invoiceExtension->setDsGiftcardCodes($giftcardInvoiceItems);
        $invoice->setExtensionAttributes($invoiceExtension);
        return $invoice;
    }
}
