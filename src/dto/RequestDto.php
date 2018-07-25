<?php

namespace app\dto;

use app\infrastructure\RequestInterface;

/**
 * Class RequestDto
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\dto
 */
class RequestDto implements RequestInterface
{
    /**
     * @var string
     */
    private $foo;

    /**
     * @var string
     */
    private $bar;

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     * @return RequestDto
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;

        return $this;
    }

    /**
     * @return string
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string $bar
     * @return RequestDto
     */
    public function setBar($bar)
    {
        $this->bar = $bar;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'foo' => $this->getFoo(),
            'bar' => $this->getBar(),
        ];
    }
}