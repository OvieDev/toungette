<?php

namespace OvieDev\Toungette;

use Exception;

class Scheme
{
    public string $fallback;
    public readonly array $json;
    private array $schema;
    private array $keys;

    public function __construct($file, $fallback)
    {
        $json_raw = file_get_contents($file);
        if (!$json_raw) {
            throw new Exception("Couldn't find file or open it");
        }
        $this->json = json_decode($json_raw, true);
        if (!isset($this->json)) {
            throw new Exception("Invalid JSON");
        }
        $this->schema = $this->json['schema'];
        $this->keys = $this->json['keys'];
        foreach ($this->keys as $key) {
            if (count($key)!=count($this->schema)) {
                throw new Exception("Key breaks schema");
            }
        }
        $this->fallback = $fallback;
    }

    public function reset_scheme(): void
    {
        $this->schema = $this->json['schema'];
        $this->keys = $this->json['keys'];
    }

    public function modify_key(string $keyname, array $keys): void
    {
        if (count($keys)!=count($this->schema)) {
            throw new Exception("Key order doesn't match the schema");
        }
        $this->keys[$keyname] = $keys;
    }

    public function delete_key(string $keyname): void
    {
        if (!array_key_exists($keyname, $this->keys)) {
            throw new Exception("Key doesn't exists");
        }

        unset($this->keys[$keyname]);
    }

    public function push_to_schema(string $lang, bool $use_fallback, array $values): void
    {
        $this->schema[] = $lang;
        if ($use_fallback) {
            foreach ($this->keys as &$key) {
                $key[] = $this->fallback;
            }
        }
        else {
            if (count($values)==count($this->keys)) {
                $i = 0;
                foreach ($this->keys as &$key) {
                    $key[] = $values[$i];
                    $i++;
                }
            }
            else {
                throw new Exception('$values don\'t match keys number');
            }
        }
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function getSchema()
    {
        return $this->schema;
    }

}