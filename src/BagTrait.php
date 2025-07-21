<?php

namespace Pebble\Template;

trait BagTrait
{
    private array $data = [];

    public function import(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function set(string $name, mixed $value): static
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function add(string $name, mixed $value, ?string $index = null): static
    {
        if (is_array($this->data[$name] ?? [])) {
            $index
                ? ($this->data[$name][$index] = $value)
                : ($this->data[$name][] = $value);
        }
        return $this;
    }

    public function get(string $name): mixed
    {
        return  $this->data[$name] ?? null;
    }

    public function export(): array
    {
        return $this->data;
    }
}
