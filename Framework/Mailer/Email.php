<?php

declare(strict_types=1);

namespace Framework\Mailer;

final readonly class Email
{
    public function __construct(
        private string $from,
        private string $to,
        private string $subject,
        private string $template,
        private array $options = [],
    ) {
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
