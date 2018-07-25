<?php

namespace tests\impl\cache;

use app\impl\cache\CacheItem;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheItemTest
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package tests\impl\cache
 */
class CacheItemTest extends TestCase
{
    public function providerExpiresAfter()
    {
        $delta = 10;
        $interval = new \DateInterval('PT' . $delta . 'H');

        return [
            [$interval, (new \DateTime())->add($interval)->getTimestamp()],
            [$delta * 3600, (new \DateTime())->modify('+' . $delta . ' hour')->getTimestamp()],
            [null, time()],
        ];
    }

    /**
     * @param \DateInterval|int|null $time
     * @param string $needle
     *
     * @covers CacheItem::expiresAfter()
     * @dataProvider providerExpiresAfter
     */
    public function testExpiresAfter($time, $needle)
    {
        // arrange
        $item = $this->getItem('foo', 'bar');

        // act
        $item->expiresAfter($time);

        // assert
        $this->assertEquals($needle, $item->getExpiresAt()->getTimestamp(), '', 5);
    }

    public function providerIsHit()
    {
        return [
            ['bar', (new \DateTime())->modify('+1 hour'), true],
            [null, (new \DateTime())->modify('+1 hour'), false],
            ['bar', (new \DateTime())->modify('-1 hour'), false],
        ];
    }

    /**
     * @param mixed $value
     * @param \DateTimeInterface $expireAt
     * @param bool $result
     *
     * @covers CacheItem::isHit()
     * @dataProvider providerIsHit
     */
    public function testIsHit($value, $expireAt, $result)
    {
        // arrange
        $item = $this->getItem('foo', $value);
        $item->expiresAt($expireAt);

        // act

        // assert
        $this->assertSame($result, $item->isHit());
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return CacheItem
     */
    private function getItem($key, $value = null)
    {
        return new CacheItem($key, $value);
    }
}