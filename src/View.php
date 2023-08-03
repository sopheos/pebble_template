<?php

namespace Pebble\Template;

use InvalidArgumentException;
use Stringable;

class View implements Stringable
{
    use BagTrait;
    use HelperTrait;

    private string $file;

    // -------------------------------------------------------------------------

    /**
     * @param string $file
     */
    public function __construct(string $file, array $data = [])
    {
        $this->file = $file;
        $this->import($data);
    }

    /**
     * @param string $file
     * @param array $bag
     * @return static
     */
    public static function create(string $file, array $data = []): static
    {
        return new static($file, $data);
    }

    /**
     * @param string $file
     * @param array $data
     * @return static
     */
    public function include(string $file, array $data = []): static
    {
        return new static($file, $data + $this->export());
    }

    // -------------------------------------------------------------------------

    /**
     * @return string
     */
    public function load(): string
    {
        if (!$this->file || !is_file($this->file)) {
            throw new InvalidArgumentException("File not found {$this->file}");
        }

        if ($this->data) {
            extract($this->data);
        }

        ob_start();
        include $this->file;
        return ob_get_clean() ?: '';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->load();
    }

    // -------------------------------------------------------------------------
}
