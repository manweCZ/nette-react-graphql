<?php
namespace ApiModule\Model;

use Psr\Log\LoggerInterface;
use Tracy\ILogger;

class ApiLogger implements LoggerInterface
{
    private ILogger $logger;


    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logger->log($message, $level);
        $this->logger->log($context, $level);
    }
}
