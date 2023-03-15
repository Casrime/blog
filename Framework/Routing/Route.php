<?php

declare(strict_types=1);

namespace Framework\Routing;

class Route
{
    public function __construct(
        private string $path,
        private string $name,
        /**
         * @var array<string, string>
         */
        private array $controller,
        /**
         * @var array<string>
         */
        private array $methods = [],
        /**
         * @var array<string>
         */
        private array $arguments = [],
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string, string>
     */
    public function getController(): array
    {
        return $this->controller;
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param string[] $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string[] $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function hasArgument()
    {
    }

    public function getArgument(string $name): ?string
    {
        return $this->arguments[$name] ?? null;
    }

    public function setArgument(string $name, string $value): void
    {
        $this->arguments[$name] = $value;
    }

    public function defineArgumentName(string $parameter): string
    {
        return $this->removeSpecialChars($parameter);
    }

    public function defineArgumentValue(string $currentRequest): string
    {
        $prefix = '';
        $length = min(strlen($this->getPath()), strlen($currentRequest));

        for ($i = 0; $i < $length; ++$i) {
            if ($this->getPath()[$i] !== $currentRequest[$i]) {
                break;
            }
            $prefix .= $this->getPath()[$i];
        }

        $pathWithoutPrefix = substr($this->getPath(), strlen($prefix));
        $currentRequestWithoutPrefix = substr($currentRequest, strlen($prefix));

        return str_replace($pathWithoutPrefix, '/'.$currentRequestWithoutPrefix, $pathWithoutPrefix);
    }

    public function removeSpecialChars(string $parameter): string
    {
        // TODO - handle other chars ? (show generate method if needed)
        return preg_replace('#/|{|}#', '', $parameter);
    }

    public function generate(string $string): string
    {
        $string = preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
        $string = preg_replace('#à|á|â|ã|ä|å#', 'a', $string);
        $string = preg_replace('#ç#', 'c', $string);
        $string = preg_replace('#è|é|ê|ë#', 'e', $string);
        $string = preg_replace('#ì|í|î|ï#', 'i', $string);
        $string = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $string);
        $string = preg_replace('#ù|ú|û|ü#', 'u', $string);
        $string = preg_replace('#ý|ÿ#', 'y', $string);
        $string = preg_replace('#|_|:|,|\'|"#', '', $string);

        return $string;
    }

    public function updatePath(): void
    {
        foreach ($this->getArguments() as $key => $argument) {
            $this->setPath(preg_replace('#/{'.$key.'}#', $argument, $this->getPath()));
        }
    }
}
