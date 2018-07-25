<?php

namespace app\services;

use app\dto\ErrorDto;
use app\infrastructure\DataProvider;
use app\infrastructure\RequestInterface;
use app\infrastructure\ResponseDto;
use app\infrastructure\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class SomeService
 *
 * @author Ilya Kolesnikov <igkolesn@mts.ru>
 * @package app\services
 */
class SomeService
{
    /**
     * @var DataProvider
     */
    private $provider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SomeService constructor.
     *
     * @param DataProvider $provider
     * @param LoggerInterface $logger
     */
    public function __construct(DataProvider $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function someMethod(RequestInterface $request)
    {
        $response = new ResponseDto();

        try {
            $result = $this->provider->get($request->toArray());
            $response->setResult($result);
        } catch (\Exception $e) {
            $error = new ErrorDto($e->getMessage(), $e->getCode());
            $response->setError($error);

            $this->logger->critical('Error occurred: ' . $error->getMessage());
        }

        return $response;
    }
}