<?php

declare(strict_types=1);

namespace Framework\Validator\Constraints;

final class Length
{
    public int $min = 0;
    public int $max = 255;
    public string $message = 'This value should have at least {{ min }} characters and {{ max }} characters at most.';

    public function __construct(array $options = [])
    {
        $this->hydrate($options);
    }

    private function hydrate(array $options): void
    {
        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function validate($value): bool
    {
        if (strlen($value) < $this->min || strlen($value) > $this->max) {
            return true;
        }

        return false;
    }
}
