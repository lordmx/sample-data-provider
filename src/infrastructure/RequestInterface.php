<?php

namespace app\infrastructure;

/**
 * Interface RequestInterface
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\infrastructure
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function toArray();
}