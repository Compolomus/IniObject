<?php

declare(strict_types=1);

namespace Compolomus\IniObject;

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
