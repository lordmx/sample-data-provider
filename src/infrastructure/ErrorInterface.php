<?php

namespace app\infrastructure;

/**
 * Interface ErrorInterface
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\infrastructure
 */
interface ErrorInterface
{
    /**
     * @return integer
     */
    public function getCode();

    /**
     * @return string
     */
    public function getMessage();
}