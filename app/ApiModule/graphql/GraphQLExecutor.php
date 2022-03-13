<?php

namespace ApiModule\GraphQL;

use ApiModule\Model\ApiLogger;
use Nette\DI\Container;
use Nette\Http\Request;
use Tracy\ILogger;

class GraphQLExecutor
{
    private RequestProcessor $requestProcessor;
    private Request $request;
    private ILogger $logger;
    private Container $di;

    public function __construct(
        RequestProcessor $requestProcessor,
        Request          $request,
        ILogger          $logger,
        Container        $di
    )
    {
        $this->requestProcessor = $requestProcessor;
        $this->request = $request;
        $this->logger = $logger;
        $this->di = $di;
    }

    public function execute()
    {
        $requestBody = $this->request->getRawBody(); // get body from http request
        $requestParser = new JsonRequestParser($requestBody);
        return $this->requestProcessor->process($requestParser, [], $this->di, null, null, new ApiLogger($this->logger));
    }
}
