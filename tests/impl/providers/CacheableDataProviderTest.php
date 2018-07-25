<?php

namespace tests\impl\providers;

use app\impl\cache\CacheItemPool;
use app\impl\providers\CacheableDataProvider;
use app\impl\providers\SimpleDataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheableDataProviderTest
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package tests\impl\providers
 */
class CacheableDataProviderTest extends TestCase
{
    /**
     * @covers CacheableDataProvider::get()
     */
    public function testGet_Positive()
    {
        // arrange
        $input = $this->getInput();
        $data = $this->getData(['unique' => uniqid()]);
        $provider = $this->getProvider($data);
        $cachePool = $this->getCachePool();
        $object = $this->getObject($provider, $cachePool);

        // act
        $result1 = $object->get($input);
        $provider->setData(array_merge($data, ['unique' => uniqid()]));
        $result2 = $object->get($input);

        // assert
        $this->assertSame($data, $result1);
        $this->assertSame($result1, $result2);
    }

    /**
     * @return array
     */
    private function getInput()
    {
        return [];
    }

    /**
     * @param array $data
     * @return array
     */
    private function getData(array $data = [])
    {
        return array_merge($data, ['foo' => 'bar']);
    }

    /**
     * @param SimpleDataProvider $simpleProvider
     * @param CacheItemPool $cachePool
     * @return CacheableDataProvider
     */
    private function getObject(SimpleDataProvider $simpleProvider, CacheItemPool $cachePool)
    {
        return new CacheableDataProvider($simpleProvider, $cachePool);
    }

    /**
     * @return CacheItemPool
     */
    private function getCachePool()
    {
        return new CacheItemPool();
    }

    /**
     * @param array $data
     * @return SimpleDataProvider
     */
    private function getProvider(array $data)
    {
        return new SimpleDataProvider($data);
    }
}