<?php

namespace app\infrastructure;

/**
 * Class ResponseDto
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\infrastructure
 */
class ResponseDto implements ResponseInterface
{
    /**
     * @var array
     */
    private $result = [];

    /**
     * @var ErrorInterface|null
     */
    private $error;

    /**
     * ResponseDto constructor.
     *
     * @param array $result
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return ErrorInterface|null
     */
    public function getException()
    {
        return $this->error;
    }

    /**
     * @param ErrorInterface|null $error
     * @return $this
     */
    public function setError(ErrorInterface $error = null)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param array $result
     * @return $this
     */
    public function setResult(array $result = [])
    {
        $this->result = $result;

        return $this;
    }

}