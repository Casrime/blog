<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

class Response
{
    private string $content = '';

    /**
     * @var array<string, mixed>
     */
    private array $headers = [];
    private int $statusCode;

    /**
     * @param array<string, mixed> $headers
     */
    public function __construct(?string $content, int $status = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setHeaders($headers);
    }

    public function send(): void
    {
        $this->sendContent();
    }

    private function setContent(?string $content): void
    {
        $this->content = $content ?? '';
    }

    private function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        http_response_code($this->statusCode);

        return $this;
    }

    private function sendContent(): self
    {
        echo $this->content;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param array<string, mixed> $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
}
