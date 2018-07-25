<?php

namespace app\dto;

use app\infrastructure\ErrorInterface;

/**
 * Class ErrorDto
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\dto
 */
class ErrorDto implements ErrorInterface
{
    /**
     * @var integer|null
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * ErrorDto constructor.
     *
     * @param $message
     * @param null $code
     */
    public function __construct($message, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * @return int|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}