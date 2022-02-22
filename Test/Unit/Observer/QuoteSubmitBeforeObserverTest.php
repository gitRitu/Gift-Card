<?php

namespace Dotsquare\Giftcard\Test\Unit\Observer;

use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface;
use Dotsquare\Giftcard\Observer\QuoteSubmitBeforeObserver;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event;
use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterfaceFactory as GiftcardOrderInterfaceFactory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class QuoteSubmitBeforeObserverTest
 * Test for \Dotsquare\Giftcard\Observer\QuoteSubmitBeforeObserver
 *
 * @package Dotsquare\Giftcard\Test\Unit\Observer
 */
class QuoteSubmitBeforeObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var QuoteSubmitBeforeObserver
     */
    private $object;

    /**
     * @var OrderExtensionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $orderExtensionFactoryMock;

    /**
     * @var GiftcardOrderInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $giftcardOrderFactoryMock;

    /**
     * @var DataObjectHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var DataObjectProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataObjectProcessorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->orderExtensionFactoryMock = $this->getMockBuilder(OrderExtensionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->giftcardOrderFactoryMock = $this->getMockBuilder(GiftcardOrderInterfaceFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->dataObjectHelperMock = $this->getMockBuilder(DataObjectHelper::class)
            ->setMethods(['populateWithArray'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->dataObjectProcessorMock = $this->getMockBuilder(DataObjectProcessor::class)
            ->setMethods(['buildOutputDataArray'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = $objectManager->getObject(
            QuoteSubmitBeforeObserver::class,
            [
                'orderExtensionFactory' => $this->orderExtensionFactoryMock,
                'giftcardOrderFactory' => $this->giftcardOrderFactoryMock,
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'dataObjectProcessor' => $this->dataObjectProcessorMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $baseDsGiftcardAmount = 10;
        $dsGiftcardAmount = 10;
        $quoteData = [
            'id' => 1
        ];

        $orderMock = $this->getMockForAbstractClass(
            OrderInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['setBaseDsGiftcardAmount', 'setDsGiftcardAmount']
        );
        $quoteMock = $this->getMockForAbstractClass(
            CartInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getBaseDsGiftcardAmount', 'getDsGiftcardAmount']
        );

        $eventMock = $this->getMockBuilder(Event::class)
            ->setMethods(['getOrder', 'getQuote'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($orderMock);
        $eventMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($quoteMock);
        $observerMock = $this->getMockBuilder(Observer::class)
            ->setMethods(['getEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $observerMock->expects($this->exactly(2))
            ->method('getEvent')
            ->willReturn($eventMock);

        $quoteMock->expects($this->exactly(3))
            ->method('getBaseDsGiftcardAmount')
            ->willReturn($baseDsGiftcardAmount);
        $quoteMock->expects($this->once())
            ->method('getDsGiftcardAmount')
            ->willReturn($dsGiftcardAmount);

        $orderMock->expects($this->once())
            ->method('setBaseDsGiftcardAmount')
            ->with($baseDsGiftcardAmount)
            ->willReturnSelf();
        $orderMock->expects($this->once())
            ->method('setDsGiftcardAmount')
            ->with($DsGiftcardAmount)
            ->willReturnSelf();
        $orderMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn(null);

        $orderExtensionMock = $this->getMockForAbstractClass(
            OrderExtensionInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['setDsGiftcardCodes']
        );
        $this->orderExtensionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($orderExtensionMock);

        $quoteExtensionMock = $this->getMockForAbstractClass(
            CartExtensionInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getDsGiftcardCodes']
        );
        $quoteMock->expects($this->exactly(3))
            ->method('getExtensionAttributes')
            ->willReturn($quoteExtensionMock);

        $quoteGiftcardMock = $this->getMockForAbstractClass(
            GiftcardQuoteInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getData']
        );
        $quoteExtensionMock->expects($this->exactly(2))
            ->method('getDsGiftcardCodes')
            ->willReturn([$quoteGiftcardMock]);

        $orderGiftcardMock = $this->getMockForAbstractClass(GiftcardOrderInterface::class);
        $this->giftcardOrderFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($orderGiftcardMock);

        $this->dataObjectProcessorMock->expects($this->once())
            ->method('buildOutputDataArray')
            ->with($quoteGiftcardMock, QuoteInterface::class)
            ->willReturn($quoteData);
        $this->dataObjectHelperMock->expects($this->once())
            ->method('populateWithArray')
            ->with($orderGiftcardMock, $quoteData, GiftcardOrderInterface::class)
            ->willReturnSelf();

        $orderGiftcardMock->expects($this->once())
            ->method('setId')
            ->with(null);

        $orderExtensionMock->expects($this->once())
            ->method('setDsGiftcardCodes')
            ->with([$orderGiftcardMock]);
        $orderMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($orderExtensionMock);

        $this->object->execute($observerMock);
    }

    /**
     * Testing of execute method if no gifcard applied (Paypal Express compatibility)
     */
    public function testExecuteNoGiftCardApplied()
    {
        $baseDsGiftcardAmount = 0;

        $orderMock = $this->getMockForAbstractClass(
            OrderInterface::class,
            [],
            '',
            true,
            true,
            true,
            []
        );
        $quoteMock = $this->getMockForAbstractClass(
            CartInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getBaseDsGiftcardAmount']
        );

        $eventMock = $this->getMockBuilder(Event::class)
            ->setMethods(['getOrder', 'getQuote'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($orderMock);
        $eventMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($quoteMock);
        $observerMock = $this->getMockBuilder(Observer::class)
            ->setMethods(['getEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $observerMock->expects($this->exactly(2))
            ->method('getEvent')
            ->willReturn($eventMock);

        $quoteMock->expects($this->once())
            ->method('getBaseDsGiftcardAmount')
            ->willReturn($baseDsGiftcardAmount);

        $this->object->execute($observerMock);
    }
}
