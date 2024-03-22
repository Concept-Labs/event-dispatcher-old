<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Test\Cache;

use Cl\EventDispatcher\Cache\InternalCache;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
/**
 * Test cases for InternalCache class.
 *
 * @covers Cl\EventDispatcher\Cache\InternalCache
 */
class InternalCacheTest extends TestCase
{
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * Set up the test fixture.
     */
    public function setUp(): void
    {
        $this->cache = new InternalCache();
    }

    /**
     * Test for the get method.
     */
    public function testGet()
    {
        $this->cache->clear();
        $this->cache->set('key', 'value');

        $this->assertEquals('value', $this->cache->get('key'));
        $this->assertNull($this->cache->get('nonexistent_key'));
    }

    /**
     * Test for the has method.
     */
    public function testHas()
    {
        $this->cache->clear();
        $this->cache->set('key', 'value');

        $this->assertTrue($this->cache->has('key'));
        $this->assertFalse($this->cache->has('nonexistent_key'));
    }

    /**
     * Test for the set method.
     */
    public function testSet()
    {
        $this->cache->clear();

        $this->assertTrue($this->cache->set('key', 'value'));
        $this->assertEquals('value', $this->cache->get('key'));

        $this->assertTrue($this->cache->set('key', 'new_value'));
        $this->assertEquals('new_value', $this->cache->get('key'));
    }

    /**
     * Test for the delete method.
     */
    public function testDelete()
    {
        $this->cache->clear();
        $this->cache->set('key', 'value');

        $this->assertTrue($this->cache->delete('key'));
        $this->assertFalse($this->cache->has('key'));
    }

    /**
     * Test for the clear method.
     */
    public function testClear()
    {
        $this->cache->clear();
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');

        $this->assertTrue($this->cache->clear());
        $this->assertFalse($this->cache->has('key1'));
        $this->assertFalse($this->cache->has('key2'));
    }

    /**
     * Test for the getMultiple method.
     */
    public function testGetMultiple()
    {
        $this->cache->clear();
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');

        $result = $this->cache->getMultiple(['key1', 'key2', 'nonexistent_key'], 'default_value');
        $this->assertEquals(['value1', 'value2', 'default_value'], iterator_to_array($result));
    }

    /**
     * Test for the setMultiple method.
     */
    public function testSetMultiple()
    {
        $this->cache->clear();

        $values = ['key1' => 'value1', 'key2' => 'value2'];

        $this->assertTrue($this->cache->setMultiple($values));
        $this->assertEquals('value1', $this->cache->get('key1'));
        $this->assertEquals('value2', $this->cache->get('key2'));
    }

    /**
     * Test for the deleteMultiple method.
     */
    public function testDeleteMultiple()
    {
        $this->cache->clear();
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');

        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key2']));
        $this->assertFalse($this->cache->has('key1'));
        $this->assertFalse($this->cache->has('key2'));
    }
}
