<?php

namespace app\impl\cache;

use Psr\Cache\CacheItemInterface;

/**
 * Class CacheItem
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\impl\cache
 */
class CacheItem implements CacheItemInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var \DateTimeInterface|null
     */
    private $expiresAt;

    /**
     * CacheItem constructor.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __construct($key, $value = null)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function isHit()
    {
        $expired = false;

        if ($this->expiresAt) {
            $expired = $this->expiresAt < new \DateTime();
        }

        return $this->value !== null && !$expired;
    }

    /**
     * @inheritDoc
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiresAt($expiration)
    {
        $this->expiresAt = $expiration;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiresAfter($time)
    {
        $this->expiresAt = new \DateTime();

        if ($time instanceof \DateInterval) {
            $this->expiresAt->add($time);
        } elseif (is_numeric($time)) {
            $this->expiresAt->modify('+' . $time . ' second');
        }

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

}
