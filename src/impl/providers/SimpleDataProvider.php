<?php

namespace app\impl\providers;

use app\infrastructure\DataProvider;

/**
 * Class SimpleDataProvider
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\impl\providers
 */
class SimpleDataProvider implements DataProvider
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * SimpleDataProvider constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function get(array $input)
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

}