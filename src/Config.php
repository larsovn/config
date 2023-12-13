<?php

namespace Larso\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class Config
{
    private static array $data = [];

    protected Filesystem $fileSystem;

    protected string $file;

    public function __construct(string $file)
    {
        $this->file = $file;

        $this->fileSystem = new Filesystem();

        $this->loadFile();
    }

    private function loadFile(): void
    {
        if (empty($this->file)) {
            throw new \InvalidArgumentException('File config.php not set, please set config.php to init');
        }

        if (! $this->fileSystem->isFile($this->file)) {
            throw new \RuntimeException('File config.php not exists, please create file config.php return to array');
        }

        $fileData = include $this->file;

        if (! Arr::accessible($fileData)) {
            throw new \RuntimeException('File config.php must be array type');
        }

        static::$data = $fileData;
    }

    /**
     * @return static
     */
    public static function make(string $file)
    {
        return new static($file);
    }

    /**
     * Get value config by keyname
     *
     * @param  string  $keyname
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($keyname, $default = null)
    {
        return Arr::get(static::$data, $keyname, $default);
    }
}
