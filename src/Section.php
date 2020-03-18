<?php

declare(strict_types=1);

namespace Compolomus\IniObject;

use InvalidArgumentException;

class Section
{
    private $name;

    private $params;

    /**
     * Section constructor.
     *
     * @param string $sectionName
     * @param array $params
     */
    public function __construct(string $sectionName, array $params)
    {
        $this->name = $sectionName;

        $data = [];
        foreach ($params as $name => $value) {
            $data[$name] = new Param($name, $value);
        }

        $this->params = $data;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function add(string $name, $value): void
    {
        if (isset($this->params[$name])) {
            throw new InvalidArgumentException('Overwrite parameter denied');
        }
        $this->params[$name] = new Param($name, $value);
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        if (! isset($this->params[$name])) {
            throw new InvalidArgumentException('Parameter not found for remove');
        }
        unset($this->params[$name]);
    }

    public function update(string $name, $value): void
    {
        if (! isset($this->params[$name])) {
            throw new InvalidArgumentException('Parameter not found for update');
        }
        $this->params[$name] = new Param($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam(string $name)
    {
        return $this->params[$name]->getValue();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        $return = PHP_EOL . '[' . $this->getName() . ']' . PHP_EOL . PHP_EOL;
        foreach ($this->params as $param) {
            $return .= (string) $param;
        }

        return $return;
    }
}
