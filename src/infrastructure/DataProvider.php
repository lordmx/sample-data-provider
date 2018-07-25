<?php

namespace app\infrastructure;

/**
 * Interface DataProvider
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\infrastructure
 */
interface DataProvider
{
    /**
     * @param array $input
     * @return array
     */
    public function get(array $input);
}