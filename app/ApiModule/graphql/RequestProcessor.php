<?php declare(strict_types=1);

namespace ApiModule\GraphQL;

use GraphQL\Error\ClientAware;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Executor\Promise\PromiseAdapter;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\QueryDepth;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Localization\Translator;
use Portiny\GraphQL\Contract\Http\Request\RequestParserInterface;
use Portiny\GraphQL\Contract\Provider\MutationFieldsProviderInterface;
use Portiny\GraphQL\Contract\Provider\QueryFieldsProviderInterface;
use Portiny\GraphQL\GraphQL\Schema\SchemaCacheProvider;
use Psr\Log\LoggerInterface;
use Throwable;

final class RequestProcessor
{
    private bool $debugMode = false;
    private bool $schemaCache = false;
    private MutationFieldsProviderInterface $mutationFieldsProvider;
    private QueryFieldsProviderInterface $queryFieldsProvider;
    private SchemaCacheProvider $schemaCacheProvider;
    private Response $response;
    private Request $request;
    private Translator $translator;

    public function __construct(
        bool $debugMode,
        MutationFieldsProviderInterface $mutationFieldsProvider,
        QueryFieldsProviderInterface $queryFieldsProvider,
        SchemaCacheProvider $schemaCacheProvider,
        IResponse $response,
        IRequest $request,
        Translator $translator
    )
    {
        $this->debugMode = $debugMode;
        $this->mutationFieldsProvider = $mutationFieldsProvider;
        $this->queryFieldsProvider = $queryFieldsProvider;
        $this->schemaCacheProvider = $schemaCacheProvider;
        $this->response = $response;
        $this->request = $request;
        $this->translator = $translator;
    }


    public function setSchemaCache(bool $useSchemaCache): void
    {
        $this->schemaCache = $useSchemaCache;
    }


    /**
     * @param JsonRequestParser $requestParser
     * @param array $rootValue
     * @param mixed|null $context
     * @param array|null $allowedQueries
     * @param array|null $allowedMutations
     * @param LoggerInterface|null $logger
     * @return array
     * @throws Throwable
     */
    public function process(
        JsonRequestParser $requestParser,
        array $rootValue = [],
        $context = null,
        ?array $allowedQueries = null,
        ?array $allowedMutations = null,
        ?LoggerInterface $logger = null
    ): array
    {
        $debugLevel = $this->detectDebugLevel($logger);
        try {

            // cached schema
            // ------------------------------------------------------------------------------------------

            $cacheKey = $this->schemaCacheProvider->getCacheKey($allowedQueries, $allowedMutations);
            $schema = null;
            if ($this->schemaCache && $this->schemaCacheProvider->isCached($cacheKey)) {
                $schema = $this->schemaCacheProvider->getSchema($cacheKey);
            }
            if ($schema === null) {
                $schema = $this->createSchema($allowedQueries, $allowedMutations);
                if ($this->schemaCache) {
                    $this->schemaCacheProvider->save($cacheKey, $schema);
                }
            }

            // max depth 3 for IntrospectionQuery
            // ------------------------------------------------------------------------------------------

            if (strpos($requestParser->getQuery(), 'IntrospectionQuery') === false) {
                $rule = new QueryDepth(3);
                DocumentValidator::addRule($rule);
            }

            // execution
            // ------------------------------------------------------------------------------------------

            $result = GraphQL::executeQuery(
                $schema,
                $requestParser->getQuery(),
                $rootValue,
                $context,
                $requestParser->getVariables(),
                $requestParser->getOperationName()
            );

            $output = $result->toArray($debugLevel);

        } catch (Throwable $throwable) {

            if($this->debugMode && !($throwable instanceof ClientAware)) {
                throw $throwable;
            }

            if ($logger) {
                $logger->error((string) $throwable, $throwable->getTrace());
            }

            $output = [
                'errors' => [FormattedError::createFromException($throwable, DebugFlag::NONE, 'An error occurred.')],
            ];

            foreach ($output['errors'] as $index => $error) {
                if (isset($error['message'])) {
                    $output['errors'][$index]['message'] = $this->translator->translate($error['message']);
                }
            }
        }

        return $output;
    }


    /**
     * @param PromiseAdapter         $promiseAdapter
     * @param RequestParserInterface $requestParser
     * @param array                  $rootValue
     * @param mixed|null             $context
     * @param array|null             $allowedQueries
     * @param array|null             $allowedMutations
     * @param LoggerInterface|null   $logger
     * @return Promise
     */
    public function processViaPromise(
        PromiseAdapter $promiseAdapter,
        RequestParserInterface $requestParser,
        array $rootValue = [],
        $context = null,
        ?array $allowedQueries = null,
        ?array $allowedMutations = null,
        ?LoggerInterface $logger = null
    ): Promise
    {
        try {
            return GraphQL::promiseToExecute(
                $promiseAdapter,
                $this->createSchema($allowedQueries, $allowedMutations),
                $requestParser->getQuery(),
                $rootValue,
                $context,
                $requestParser->getVariables()
            );
        } catch (Throwable $throwable) {
            if ($logger) {
                $logger->error((string)$throwable);
            }

            return $promiseAdapter->createRejected($throwable);
        }
    }


    private function createSchema(?array $allowedQueries = null, ?array $allowedMutations = null): Schema
    {
        $configuration = [
            'query' => $this->createQueryObject($allowedQueries),
        ];

        $mutationObject = $this->createMutationObject($allowedMutations);
        if ($mutationObject->getFields()) {
            $configuration['mutation'] = $mutationObject;
        }

        return new Schema($configuration);
    }

    private function createQueryObject(?array $allowedQueries = null): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => $this->queryFieldsProvider->convertFieldsToArray($allowedQueries),
        ]);
    }


    private function createMutationObject(?array $allowedMutations = null): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => $this->mutationFieldsProvider->convertFieldsToArray($allowedMutations),
        ]);
    }

    private function detectDebugLevel(?LoggerInterface $logger): int
    {
        return $this->debugMode
            ? DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE
            : ($logger === null ? DebugFlag::NONE : DebugFlag::RETHROW_INTERNAL_EXCEPTIONS);
    }

}
