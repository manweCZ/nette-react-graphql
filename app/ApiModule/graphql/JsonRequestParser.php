<?php declare(strict_types = 1);

namespace ApiModule\GraphQL;

use Portiny\GraphQL\Contract\Http\Request\RequestParserInterface;

final class JsonRequestParser implements RequestParserInterface
{
	/**
	 * @var array
	 */
	private $data = [];


	public function __construct(string $json)
	{
		$this->data = json_decode($json ?: '{}', true);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getQuery(): string
	{
		return $this->data['query'] ?? '';
	}


	/**
	 * {@inheritdoc}
	 */
	public function getVariables(): array
	{
		return $this->data['variables'] ?? [];
	}

    public function getOperationName()
    {
        return $this->data['operationName'] ?? null;
    }

}
