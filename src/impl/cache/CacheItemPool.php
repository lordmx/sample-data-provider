<?php

namespace app\impl\cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CacheItemPool
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\impl\cache
 */
class CacheItemPool implements CacheItemPoolInterface
{
    /**
     * @var CacheItemInterface[]
     */
    private $items = [];

    /**
     * @var CacheItemInterface[]
     */
    private $deferred = [];

    /**
     * @inheritDoc
     */
    public function getItem($key)
    {
        if ($this->hasItem($key)) {
            return $this->items[$key];
        }

        return new CacheItem($key);
    }

    /**
     * @inheritDoc
     */
    public function getItems(array $keys = [])
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->getItem($key);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function hasItem($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->items = [];

    }

    /**
     * @inheritDoc
     */
    public function deleteItem($key)
    {
        unset($this->items[$key]);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }
    }

    /**
     * @inheritDoc
     */
    public function save(CacheItemInterface $item)
    {
        $this->items[$item->getKey()] = $item;
        return true;
    }

    /**
     * @inheritDoc
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    /**
     * @inheritDoc
     */
    public function commit()
    {
        foreach ($this->deferred as $key => $item) {
            $this->save($item);
            unset($this->deferred[$key]);
        }

        return true;
    }

    /**
     * @return CacheItemInterface[]
     */
    public function getAllItems()
    {
        return $this->items;
    }

}