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

//    /**
//     * @param $value
//     * @return mixed
//     */
//    private function bool($value)
//    {
//        $values = [
//            'true',
//            'false',
//            'null',
//            'on',
//            'off',
//            'yes',
//            'no'
//        ];
//
//        return (in_array($value, $values, true) || is_numeric($value) || is_array($value) ? $value : '"' . $value . '"');
//    }

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
