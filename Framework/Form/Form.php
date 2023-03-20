<?php

declare(strict_types=1);

namespace Framework\Form;

use App\Model\ModelInterface;
use Framework\Form\Type\AbstractType;
use Framework\HttpFoundation\Request;

class Form implements FormInterface
{
    private FieldCollection $fieldCollection;
    private string $content = '';
    private string $method = 'POST';
    private Request $request;

    public function __construct()
    {
        $this->fieldCollection = new FieldCollection();
    }

    /**
     * @return $this
     */
    public function createForm(FormTypeInterface $formType, ?ModelInterface $model = null): self
    {
        // TODO - handle models
        var_dump($model);
        $formType->buildForm();
        $this->fieldCollection = $formType->getFields();

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

    public function isSubmitted(): bool
    {
        return 'POST' === $this->request->getDataFromServer('request_method');
    }

    public function isValid()
    {
        // TODO: Implement isValid() method.
        return true;
    }

    /**
     * This method returns an array by default
     * or the object associated with if there is one.
     */
    public function getData(): array
    {
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

        return $data;
    }
}
