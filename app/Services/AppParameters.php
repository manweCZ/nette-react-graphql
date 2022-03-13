<?php
namespace App\Services;

class AppParameters
{
    public function __construct(protected array $parameters)
    {
    }

    public function getParameter(string $key, mixed $defaultValue = null): mixed{
        return $this->parameters[$key] ?? $defaultValue;
    }
}