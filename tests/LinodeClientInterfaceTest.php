<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2016 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
//----------------------------------------------------------------------

namespace Tests\Linode;

use Linode\Datacenter;
use Linode\Distribution;
use Linode\DnsZone\DnsZone;
use Linode\Enum\ServiceTypeEnum;
use Linode\Kernel;
use Linode\LinodeClient;
use Linode\Service\Service;
use Tests\Linode\Internal\ApiBridgeStub;

class LinodeClientInterfaceTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    /** @var LinodeClient */
    private $client;

    protected function setUp()
    {
        $this->client = new LinodeClient(null, 'https://api.alpha.linode.com/v4');

        $this->setProperty($this->client, 'api', new ApiBridgeStub());
    }

    public function testGetDatacenters()
    {
        $collection = $this->client->getDatacenters();

        self::assertCount(8, $collection);
    }

    public function testGetDatacenter()
    {
        $object = $this->client->getDatacenter('datacenter_6');

        self::assertInstanceOf(Datacenter::class, $object);
        self::assertEquals('Newark, NJ', $object->label);
    }

    public function testGetDistributions()
    {
        $collection = $this->client->getDistributions(false);

        self::assertCount(21, $collection);
    }

    public function testGetRecommendedDistributions()
    {
        $collection = $this->client->getDistributions(true);

        self::assertCount(9, $collection);
    }

    public function testGetDistribution()
    {
        $object = $this->client->getDistribution('distribution_126');

        self::assertInstanceOf(Distribution::class, $object);
        self::assertEquals('Ubuntu 12.04 LTS', $object->label);
    }

    public function testGetKernels()
    {
        $collection = $this->client->getKernels();

        self::assertCount(16, $collection);
    }

    public function testGetKernel()
    {
        $object = $this->client->getKernel('kernel_137');

        self::assertInstanceOf(Kernel::class, $object);
        self::assertEquals('Latest 32 bit (4.1.5-x86-linode80)', $object->label);
    }

    public function testGetServices()
    {
        $collection = $this->client->getServices();

        self::assertCount(7, $collection);
    }

    public function testGetLinodeServices()
    {
        $collection = $this->client->getServices(ServiceTypeEnum::LINODE);

        self::assertCount(2, $collection);
    }

    public function testGetService()
    {
        $object = $this->client->getService('service_112');

        self::assertInstanceOf(Service::class, $object);
        self::assertEquals('Linode 1024', $object->label);
    }

    public function testGetDnsZones()
    {
        $collection = $this->client->getDnsZones();

        self::assertCount(3, $collection);
    }

    public function testGetDnsZone()
    {
        $object = $this->client->getDnsZone('dnszone_1');

        self::assertInstanceOf(DnsZone::class, $object);
        self::assertEquals('example.com', $object->dnszone);
    }
}
