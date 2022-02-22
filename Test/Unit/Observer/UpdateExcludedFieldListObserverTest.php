<?php

namespace Dotsquare\Giftcard\Test\Unit\Observer;

use Dotsquare\Giftcard\Observer\UpdateExcludedFieldListObserver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Action\Attribute\Tab\Attributes;

/**
 * Class UpdateExcludedFieldListObserverTest
 * Test for \Dotsquare\Giftcard\Observer\UpdateExcludedFieldListObserver
 *
 * @package Dotsquare\Giftcard\Test\Unit\Observer
 */
class UpdateExcludedFieldListObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UpdateExcludedFieldListObserver
     */
    private $object;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->object = $objectManager->getObject(
            UpdateExcludedFieldListObserver::class,
            []
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $blockMock = $this->getMockBuilder(Attributes::class)
            ->setMethods(['getFormExcludedFieldList', 'setFormExcludedFieldList'])
            ->disableOriginalConstructor()
            ->getMock();
        $blockMock->expects($this->once())
            ->method('getFormExcludedFieldList')
            ->willReturn([]);
        $eventMock = $this->getMockBuilder(Event::class)
            ->setMethods(['getObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock->expects($this->once())
            ->method('getObject')
            ->willReturn($blockMock);
        $observerMock = $this->getMockBuilder(Observer::class)
            ->setMethods(['getEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($eventMock);

        $blockMock->expects($this->once())
            ->method('setFormExcludedFieldList')
            ->willReturnSelf();

        $this->object->execute($observerMock);
    }
}
