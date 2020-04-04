<?php

declare(strict_types=1);

namespace Compolomus\IniObject;

class Param
{
    private $name;

    private $value;

    /**
     * Param constructor.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $this->escape($value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return mixed
     */
    private function escape($value = '')
    {
        return empty($value) ? false : $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value ?? null;
    }

    public function __toString(): string
    {
        $return = '';

        if (is_array($this->getValue())) {
            foreach ($this->getValue() as $key => $value) {
                $return .= $this->getName() . '[' . $key . '] = ' . $this->escape($value) . PHP_EOL;
            }
        } else {
            $return = $this->getName() . ' = ' . $this->value . PHP_EOL;
        }

        return $return;
    }

    public function toArray(): array
    {
        return [$this->getName() => $this->value];
    }
}
