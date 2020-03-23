<?php

declare(strict_types=1);

namespace Compolomus\IniObject;

use InvalidArgumentException;
use SplFileObject;

class IniObject
{
    private $filename;

    private $config;

    private $sections;

    /**
     * IniObject constructor.
     *
     * @param string|null $filename
     * @param array $config
     */
    public function __construct(string $filename = null, array $config = [])
    {
        if (! count($config)) {
            $this->initDefaultConfig();
        }

        if ($filename && file_exists($filename)) {
            $data = parse_ini_file(
                $filename,
                true,
                $this->config['strict'] ? INI_SCANNER_TYPED : INI_SCANNER_NORMAL
            );

            $this->sectionLoad($data);

            echo '<pre>' . print_r($data, true) . '</pre>';

            $this->filename = $filename;
        }
    }

    /**
     * @param array $data
     */
    private function sectionLoad(array $data): void
    {
        if (! count($data)) {
            throw new InvalidArgumentException('Data is not set');
        }

        $sections = [];
        foreach ($data as $sectionName => $params) {
            $sections[$sectionName] = new Section($sectionName, $params);
        }

        $this->sections = $sections;
    }

    /**
     * @param string $name
     * @return Section
     */
    public function getSection(string $name): Section
    {
        if (! isset($this->sections[$name])) {
            throw new InvalidArgumentException('Section not found');
        }

        return $this->sections[$name];
    }

    /**
     * @param string $name
     */
    public function removeSection(string $name): void
    {
        if (! isset($this->sections[$name])) {
            throw new InvalidArgumentException('Section not found for remove');
        }
        unset($this->sections[$name]);
    }

    public function addSection(string $name, array $section): void
    {
        if (isset($this->sections[$name])) {
            throw new InvalidArgumentException('Overwrite section denied');
        }
        $this->sections[$name] = new Section($name, $section);
    }

    public function updateSection(string $name, array $section): void
    {
        if (! isset($this->sections[$name])) {
            throw new InvalidArgumentException('Section not found for update');
        }
        $this->sections[$name] = new Section($name, $section);
    }

    /**
     * default config
     */
    private function initDefaultConfig(): void
    {
        $this->config = [
            'strict'    => false,
            'overwrite' => true,
        ];
    }

    /**
     * @param string $filename
     */
    private function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function __toString()
    {
        $return = '';

        foreach ($this->sections as $section) {
            $return .= (string) $section;
        }

        return $return;
    }

    /**
     * @param string|null $filename
     * @return bool
     */
    public function save(string $filename = null): bool
    {
        if ($filename) {
            $this->setFilename($filename);
        }

        if (! $this->config['overwrite'] && file_exists($filename)) {
            throw new InvalidArgumentException('Overwrite file protection');
        }

        $file = new SPLFileObject($this->filename, 'r+b');
        return (bool) $file->fwrite((string) $this);
    }
}
