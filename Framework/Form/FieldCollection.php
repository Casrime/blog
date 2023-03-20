<?php

declare(strict_types=1);

namespace Framework\Form;

use Framework\Form\Type\AbstractTypeInterface;
use Framework\Form\Type\TextType;

// TODO - rename this CollectionType and implements CollectionTypeInterface ?
final class FieldCollection
{
    /**
     * @var array<AbstractTypeInterface>
     */
    private array $fields = [];

    /**
     * @return array<AbstractTypeInterface>
     */
    public function all(): array
    {
        return $this->fields;
    }

    public function add(string $string, ?string $type = null, array $options = []): self
    {
        if (null === $type) {
            $type = TextType::class;
        }

        $this->fields[] = new $type($string, $type, $options);

        return $this;
    }
}
