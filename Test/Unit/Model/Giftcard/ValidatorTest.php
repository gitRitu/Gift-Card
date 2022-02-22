<?php

namespace Dotsquare\Giftcard\Test\Unit\Model\Giftcard;

use Dotsquare\Giftcard\Api\Data\GiftcardInterface;
use Dotsquare\Giftcard\Model\Giftcard\Validator;
use Dotsquare\Giftcard\Model\Source\Giftcard\Status;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class ValidatorTest
 * Test for \Dotsquare\Giftcard\Model\Giftcard\Validator
 *
 * @package Dotsquare\Giftcard\Test\Unit\Model\Giftcard
 */
class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Validator
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
            Validator::class,
            []
        );
    }

    /**
     * Testing of isValid method
     *
     * @param int $state
     * @param bool $expectedValue
     * @dataProvider dataProviderIsValid
     */
    public function testIsValid($state, $expectedValue)
    {
        $giftcardMock = $this->getMockForAbstractClass(GiftcardInterface::class);
        $giftcardMock->expects($this->exactly(3))
            ->method('getState')
            ->willReturn($state);

        $this->assertEquals($expectedValue, $this->object->isValid($giftcardMock));
    }

    /**
     * Retirieve data provider for testIsValid method
     *
     * @return []
     */
    public function dataProviderIsValid()
    {
        return [
            [Status::ACTIVE, true],
            [Status::DEACTIVATED, false],
            [Status::EXPIRED, false],
            [Status::USED, false]
        ];
    }
}
