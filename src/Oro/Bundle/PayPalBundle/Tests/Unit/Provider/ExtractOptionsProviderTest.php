<?php

namespace Oro\Bundle\PayPalBundle\Tests\Unit\Provider;

use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\EntityBundle\ORM\EntityAliasResolver;
use Oro\Bundle\PaymentBundle\Event\ExtractLineItemPaymentOptionsEvent;
use Oro\Bundle\PaymentBundle\Event\ExtractAddressOptionsEvent;
use Oro\Bundle\PayPalBundle\Provider\ExtractOptionsProvider;
use Oro\Bundle\PayPalBundle\Tests\Unit\Method\EntityStub;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ExtractOptionsProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dispatcherMock;

    /** @var EntityAliasResolver|\PHPUnit_Framework_MockObject_MockObject */
    private $aliasProviderMock;

    /** @var ExtractOptionsProvider */
    private $provider;

    protected function setUp()
    {
        $this->dispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->aliasProviderMock = $this->getMockBuilder(EntityAliasResolver::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->provider = new ExtractOptionsProvider($this->dispatcherMock, $this->aliasProviderMock);
    }

    public function testGetShippingAddressOptions()
    {
        $entity = new \stdClass();
        $classname = \stdClass::class;
        $keys = [];
        $entityAlias = 'stdclass';
        $event = new ExtractAddressOptionsEvent($entity, $keys);

        $this->aliasProviderMock->expects($this->once())->method('getAlias')->with($classname)
            ->willReturn($entityAlias);

        $this->dispatcherMock->expects($this->once())->method('dispatch')
            ->with(ExtractAddressOptionsEvent::NAME . '.stdclass', $event);

        $this->provider->getShippingAddressOptions($classname, $entity, $keys);
    }

    public function testGetLineItemPaymentOptions()
    {
        /** @var AbstractAddress|\PHPUnit_Framework_MockObject_MockObject $abstractAddressMock */
        $abstractAddressMock = $this->getMockBuilder(AbstractAddress::class)->getMock();
        $entity = new EntityStub($abstractAddressMock);
        $event = new ExtractLineItemPaymentOptionsEvent($entity, []);

        $this->dispatcherMock->expects($this->once())->method('dispatch')
            ->with(ExtractLineItemPaymentOptionsEvent::NAME, $event);

        $this->provider->getLineItemPaymentOptions($entity, []);
    }
}
