<?php

namespace App\ApiModule\Presenters;

use ApiModule\GraphQL\GraphQLExecutor;
use App\ApiModule\ApiBasePresenter;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\Responses\JsonResponse;
use Portiny\GraphQL\GraphQL\Schema\SchemaCacheProvider;

class ApiPresenter extends ApiBasePresenter
{

    public function __construct(
        protected GraphQLExecutor     $graphQLExecutor,
        protected SchemaCacheProvider $cacheProvider)
    {
        parent::__construct();
    }

    #[NoReturn] public function startup()
    {
        parent::startup();
        $res = $this->graphQLExecutor->execute();
        $res = $this->addDebugBar($res);
        $this->sendResponse(new JsonResponse($res));
    }
}

