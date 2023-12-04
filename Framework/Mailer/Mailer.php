<?php

declare(strict_types=1);

namespace Framework\Mailer;

use Twig\Environment;

final readonly class Mailer implements MailerInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function send(Email $email): void
    {
        $headers = [
            'From' => $email->getFrom(),
            'Reply-To' => $email->getFrom(),
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=utf-8',
        ];

        $content = $this->twig->render($email->getTemplate(), $email->getOptions());

        mail(
            $email->getTo(),
            $email->getSubject(),
            $content,
            $headers
        );
    }
}
