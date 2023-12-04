<?php

declare(strict_types=1);

namespace Framework\Mailer;

interface MailerInterface
{
    public function send(Email $email): void;
}
