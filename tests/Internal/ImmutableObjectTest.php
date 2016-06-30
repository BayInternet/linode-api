<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2016 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
//----------------------------------------------------------------------

namespace Tests\Linode\Internal;

use Linode\LinodeClient;
use Tests\Linode\TestTrait;

class ImmutableObjectTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    /** @var LinodeClient */
    private $client;

    protected function setUp()
    {
        $this->client = new LinodeClient(null, 'https://api.alpha.linode.com/v4');
    }

    public function testConstructorValidData()
    {
        /** @var \StdClass $object */
        $object = new ImmutableObjectStub($this->client, ['flag' => true]);

        self::assertTrue(isset($object->flag));
        self::assertFalse(isset($object->unknown));

        self::assertTrue($object->flag);
    }

    /**
     * @expectedException \Linode\ValidationException
     * @expectedExceptionMessage [flag] This value should be of type bool.
     */
    public function testConstructorInvalidData()
    {
        new ImmutableObjectStub($this->client, ['flag' => 'true']);
    }

    /**
     * @expectedException \Linode\ValidationException
     * @expectedExceptionMessage [flag] This value should not be null.
     */
    public function testConstructorEmptyData()
    {
        new ImmutableObjectStub($this->client);
    }

    public function testGetInstance()
    {
        $reflectionMethod = new \ReflectionMethod(ImmutableObjectStub::class, 'getInstance');
        $reflectionMethod->setAccessible(true);
        $object = $reflectionMethod->invoke(null, $this->client, 'test', ['flag' => true]);

        self::assertInstanceOf(ImmutableObjectStub::class, $object);
        self::assertEquals('test', $object->id);
        self::assertTrue($object->flag);
    }

    /**
     * @expectedException \Linode\ValidationException
     * @expectedExceptionMessage This object is immutable.
     */
    public function testImmutableProperty()
    {
        $object       = new ImmutableObjectStub($this->client, ['flag' => true]);
        $object->flag = false;
    }

    public function testGetEndpoint()
    {
        $object = new ImmutableObjectStub($this->client, ['flag' => true]);
        self::assertEquals('/tests', $object->getEndpoint());

        $this->setProtectedProperty($object, 'id', 'test_123');
        self::assertEquals('/tests/test_123', $object->getEndpoint());
    }
}
