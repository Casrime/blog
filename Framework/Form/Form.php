<?php

declare(strict_types=1);

namespace Framework\Form;

use Framework\Database\Model\ModelInterface;
use Framework\Form\Type\AbstractType;
use Framework\HttpFoundation\Request;

class Form implements FormInterface
{
    private FieldCollection $fieldCollection;

    private ?string $action = null;
    private string $content = '';
    private string $method = 'POST';
    private Request $request;
    private ?ModelInterface $model = null;
    private array $errors = [];

    public function __construct()
    {
        $this->fieldCollection = new FieldCollection();
    }

    /**
     * @return $this
     */
    public function createForm(FormTypeInterface $formType, ?ModelInterface $model = null): self
    {
        //var_dump($model->{'get'.ucfirst('title()')});
        // TODO - handle models
        $this->model = $model;
        $formType->buildForm();
        $this->fieldCollection = $formType->getFields();
        //var_dump($this->fieldCollection);

        foreach ($this->fieldCollection->all() as $abstractType) {
            $abstractType->setValue($model->{'get'.ucfirst($abstractType->getName())}());
        }

        return $this;
    }

    public function handleRequest(Request $request): self
    {
        $this->request = $request;

        foreach ($this->fieldCollection->all() as $abstractType) {
            foreach ($this->request->request->all() as $requestKey => $requestValue) {
                if ($abstractType->getName() === $requestKey) {
                    $abstractType->setValue($requestValue);
                }
            }
        }

        return $this;
    }

    public function createView(): self
    {
        echo $this->content;

        return $this;
    }

    public function getFieldCollection(): FieldCollection
    {
        return $this->fieldCollection;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function isSubmitted(): bool
    {
        return $this->method === $this->request->getDataFromServer('request_method');
    }

    public function isValid(): bool
    {
        $this->validate($this->fieldCollection->all());

        if (!empty($this->errors)) {
            return false;
        }

        if (null !== $this->model) {
            $this->setData($this->fieldCollection->all());
        }

        return true;
    }

    /**
     * This method returns an array by default
     * or the object associated with if there is one.
     */
    public function getData(): array|ModelInterface
    {
        if (null !== $this->model) {
            // TODO - do we need to clear also in this case ?

            return $this->model;
        }
        // TODO: handle model case.
        // TODO - replace type by AbstractTypeInterface and check autocompletion still works
        // TODO - don't forget to add forgotten methods in AbstractTypeInterface
        $data = [];
        /** @var AbstractType $abstractType */
        foreach ($this->fieldCollection->all() as $abstractType) {
            if ('' !== $abstractType->getValue()) {
                $data[$abstractType->getName()] = $abstractType->getValue();
            }
        }

        $this->clear();

        return $data;
    }

    public function clear(): void
    {
        foreach ($this->fieldCollection->all() as $field) {
            $field->setValue('');
        }
    }

    public function setData(array $data): void
    {
        $reflect = new \ReflectionClass($this->model);
        $attributes = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);

        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            if (isset($data[$attributeName])) {
                /** @var AbstractType $abstractType */
                $abstractType = $data[$attributeName];
                $this->model->{'set'.ucfirst($attributeName)}($abstractType->getValue());
            }
        }
    }

    private function validate(array $fields): void
    {
        /** @var AbstractType $field */
        foreach ($fields as $field) {
            if (isset($field->getOptions()['constraints']) && !empty($field->getOptions()['constraints'])) {
                foreach ($field->getOptions()['constraints'] as $constraint) {
                    if ($constraint->validate($field->getValue())) {
                        $this->errors[$field->getName()] = $constraint->message;
                        // TODO - move this to a specific method named generateMessageRawError ?
                        // Replace dynamic values in constraint message
                        preg_replace_callback_array(
                            [
                                '/{{ {1}[a-z]* {1}}}/' => function ($match) use ($constraint) {
                                    return $constraint->message = str_replace($match[0], (string) $constraint->{substr($match[0], 3, -3)}, $constraint->message);
                                }
                            ],
                            $constraint->message
                        );
                        $field->setErrors([$constraint->message]);
                        break;
                    }
                }
            }
        }
    }
}
