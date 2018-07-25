<?php

namespace app\impl\providers;

use app\infrastructure\DataProvider;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CacheableDataProvider
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\impl\providers
 */
class CacheableDataProvider implements DataProvider
{
    const LOCK_TTL = 3600;

    const PROP_VALUE = 'value';
    const PROP_EXPIRES = 'expires';

    /**
     * @var DataProvider
     */
    private $provider;

    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * CacheableDataProvider constructor.
     *
     * @param DataProvider $provider
     * @param CacheItemPoolInterface $cachePool
     */
    public function __construct(DataProvider $provider, CacheItemPoolInterface $cachePool)
    {
        $this->provider = $provider;
        $this->cachePool = $cachePool;
    }

    /**
     * @inheritDoc
     */
    public function get(array $input)
    {
        $key = $this->getKey($input);
        $item = $this->cachePool->getItem($key);

        if ($item->isHit()) {
            $data = $this->decodeValue($item->get());

            if (!$this->isExpired($data) || !$this->lock($key)) {
                return $this->getValue($data);
            }
        }

        $this->lock($key);

        $value = $this->provider->get($input);

        $item->set($this->encodeValue($value))->expiresAt($this->getExpiresAt('+' . self::LOCK_TTL . ' second'));
        $this->cachePool->save($item);

        $this->unlock($key);

        return (array)$value;
    }

    /**
     * @return DataProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function getValue(array $data)
    {
        return isset($data[self::PROP_VALUE]) ? $data[self::PROP_VALUE] : null;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isExpired($data)
    {
        if (!isset($data[self::PROP_EXPIRES]) || !array_key_exists(self::PROP_VALUE, $data)) {
            return true;
        }

        return $data[self::PROP_EXPIRES] < time();
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function encodeValue($value)
    {
        $data = $this->formatValue($value, $this->getExpiresAt());

        return json_encode($data);
    }

    /**
     * @param mixed $value
     * @param \DateTimeInterface $expiresAt
     * @return array
     */
    private function formatValue($value, \DateTimeInterface $expiresAt)
    {
        return [
            self::PROP_VALUE => $value,
            self::PROP_EXPIRES => $expiresAt->getTimestamp(),
        ];
    }

    /**
     * @param string $value
     * @return array
     */
    private function decodeValue($value)
    {
        $result = json_decode($value, true);

        if (json_last_error()) {
            $result = $this->formatValue(null, new \DateTime());
        }

        return $result;
    }

    /**
     * @param string|null $modify
     * @return \DateTimeInterface
     */
    private function getExpiresAt($modify = null)
    {
        $date = new \DateTime();

        if ($modify) {
            $date->modify($modify);
        }

        return $date;
    }

    /**
     * @param array $input
     * @return string
     */
    private function getKey(array $input)
    {
        return json_encode($input);
    }

    /**
     * @param string $key
     * @return string
     */
    private function getLockKey($key)
    {
        return 'lock:' . $key;
    }

    /**
     * @param string $key
     * @return bool
     */
    private function lock($key)
    {
        $item = $this->cachePool->getItem($this->getLockKey($key));

        if ($item->isHit()) {
            return false;
        }

        $item->set(true)->expiresAfter(self::LOCK_TTL);

        return true;
    }

    /**
     * @param string $key
     */
    private function unlock($key)
    {
        $this->cachePool->deleteItem($this->getLockKey($key));
    }
}