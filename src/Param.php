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
     * @return string
     */
    private function escape($value = ''): string
    {
        return preg_match('#\s+#', $value)
            ? '"' . $value . '"'
            : (empty($value)
                ? '""'
                : $this->bool($value));
    }

    /**
     * @param $value
     * @return string
     */
    private function bool($value): string
    {
        if ($value === true) {
            return 'true';
        }
        if ($value === false) {
            return 'false';
        }
        if ($value === null) {
            return 'null';
        }
        return $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        $return = '';

        if (is_array($this->value)) {
            foreach ($this->value as $key => $value) {
                $return .= $this->name . '[' . $key . '] = ' . $this->escape($value) . PHP_EOL;
            }
        } else {
            $return = $this->name . ' = ' . $this->value . PHP_EOL;
        }

        return $return;
    }
}
