<?php

declare(strict_types=1);

namespace Framework\Form\Type;

abstract class AbstractType implements AbstractTypeInterface
{
    private string $name;
    private string $type;
    private array $options;
    private array $errors = [];

    private mixed $value = null;

    protected string $content = '';

    public function __construct(string $name, $type, $options = [])
    {
        $this->name = $name;
        $this->type = $this->setType($type);
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function getFirstError(): string
    {
        if (empty($this->errors)) {
            return '';
        }

        return '<div class="invalid-feedback">'.$this->errors[0].'</div>';
    }

    public function setType(string $type): string
    {
        $removeNamespace = str_replace(__NAMESPACE__.'\\', '', $type);
        $stringType = str_replace('Type', '', $removeNamespace);

        return strtolower($stringType);
    }

    public function get(string $key): mixed
    {
        return $this->options[$key] ?? null;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function generateOpenBlock(): string
    {
        return '<div class="mb-3">';
    }

    public function generateCloseBlock(): string
    {
        return '</div>';
    }

    public function getLabel(): string
    {
        if (null === $this->get('label')) {
            return '';
        }

        return '<label for="'.$this->getName().'" class="form-label">'.$this->get('label').'</label>';
    }

    public function getHelpBlock(): string
    {
        if (null === $this->get('help')) {
            return '';
        }

        return '<div id="'.$this->getName().'Help" class="form-text">'.$this->get('help').'</div>';
    }

    public function getHelp(): string
    {
        return $this->get('help') ? 'aria-describedby="'.$this->getName().'Help"' : '';
    }

    public function getPlaceholder(): string
    {
        return $this->get('placeholder') ? 'placeholder="'.$this->get('placeholder').'"' : '';
    }

    public function getRequired(): string
    {
        return $this->get('required') ? 'required' : '';
    }

    public function getDisabled(): string
    {
        return $this->get('disabled') ? 'disabled' : '';
    }

    public function getReadonly(): string
    {
        return $this->get('readonly') ? 'readonly' : '';
    }

    abstract public function generateHtml(): string;
}
