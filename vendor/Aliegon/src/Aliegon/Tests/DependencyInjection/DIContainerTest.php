<?php
namespace Aliegon\Tests\DependencyInjection;

use \Aliegon\DependencyInjection\DIContainer;
use \Aliegon\Tests\DependencyInjection\Service;
use \Aliegon\Auth\Auth;
class DIContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testDiContainerStoreStringAndReturnTheSame()
    {
        $dic = new DIContainer();
        $dic['string'] = 'value';

        $this->assertEquals('value', $dic['string']);
    }

    public function testDiContainerStoreClosureReturnsContent()
    {
        $dic = new DIContainer();
        $dic['service'] = function () {
            return new Service();
        };

        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $dic['service']);
    }

    public function testDiContainerStoreClosureReturnsEverytimeADifferentObject()
    {
        $dic = new DIContainer();
        $dic['service'] = function () {
            return new Service();
        };

        $serviceOne = $dic['service'];
        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $serviceOne);

        $serviceTwo = $dic['service'];
        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $serviceTwo);

        $this->assertNotSame($serviceOne, $serviceTwo);
    }

    public function testDiContainerStoreObjectReturnsEverytimeTheSameObject()
    {
        $dic = new DIContainer();
        $dic['service'] = new Service();

        $serviceOne = $dic['service'];
        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $serviceOne);

        $serviceTwo = $dic['service'];
        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $serviceTwo);

        $this->assertSame($serviceOne, $serviceTwo);
    }

    public function testDiContainerShouldPassItselfAsAParameter()
    {
        $dic = new DIContainer();
        $dic['service'] = function () {
            return new Service();
        };
        $dic['container'] = function ($container) {
            return $container;
        };

        $this->assertNotSame($dic, $dic['service']);
        $this->assertSame($dic, $dic['container']);
    }

    public function testDiContainerIsset()
    {
        $dic = new DIContainer();
        $dic['param'] = 'value';
        $dic['service'] = function () {
            return new Service();
        };

        $dic['null'] = null;

        $this->assertTrue(isset($dic['param']));
        $this->assertTrue(isset($dic['service']));
        $this->assertTrue(isset($dic['null']));
        $this->assertFalse(isset($dic['non_existent']));
    }

    public function testDiContainerShareShouldReturnAlwaysTheSameObjectEvenIfItIsAClosure()
    {
        $dic = new DIContainer();
        $dic['shared_service'] = $dic->share(function () {
            return new Service();
        });

        $serviceOne = $dic['shared_service'];
        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $serviceOne);

        $serviceTwo = $dic['shared_service'];
        $this->assertInstanceOf('Aliegon\Tests\DependencyInjection\Service', $serviceTwo);

        $this->assertSame($serviceOne, $serviceTwo);
    }
}