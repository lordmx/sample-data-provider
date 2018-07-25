<?php

namespace app\infrastructure;

/**
 * Interface ResponseInterface
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\infrastructure
 */
interface ResponseInterface
{
    /**
     * @return array
     */
    public function getResult();

    /**
     * @return null|\Exception
     */
    public function getException();
}