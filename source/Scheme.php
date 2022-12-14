<?php

namespace OvieDev\Toungette;

use Exception;

class Scheme
{
    public string $fallback;
    public string $default_lang;
    public readonly array $json;
    private array $schema;
    private array $keys;
    private array $namespaces = [];

    private function namespace_operation($func) {
        foreach ($this->namespaces as &$namespace) {
            foreach ($namespace as &$key) {
                $func($key);
            }
        }
    }

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
        $this->default_lang = $this->schema[0];
        $this->keys = $this->json['keys'];
        foreach ($this->keys as $key) {
            if (count($key)!=count($this->schema)) {
                throw new Exception("Key breaks schema");
            }
        }
        $looper = $this->json;
        while($val = current($looper))
        {
            if(key($looper)!="keys" and key($looper)!="schema") {
                $this->namespaces[key($looper)] = $val;
            }
            next($looper);
        }
        $this->fallback = $fallback;
    }

    public function reset_scheme(): void
    {
        $this->schema = $this->json['schema'];
        $this->keys = $this->json['keys'];
        $this->namespaces = [];

        $looper = $this->json;
        while($val = current($looper))
        {
            if(key($looper)!="keys" and key($looper)!="schema") {
                $this->namespaces[key($looper)] = $val;
            }
            next($looper);
        }
    }

    public function modify_key(string $keyname, array $values): void
    {
        if (count($values)!=count($this->schema)) {
            throw new Exception("Key order doesn't match the schema");
        }
        $this->keys[$keyname] = $values;
    }

    public function delete_key(string $keyname, string $namespace=""): void
    {
        if($namespace=="")
        {
            $target = &$this->keys;
        }
        else {
            $target = &$this->namespaces[$namespace];
        }
        if (!array_key_exists($keyname, $target)) {
            throw new Exception("Key doesn't exists");
        }

        unset($target[$keyname]);
    }

    public function push_to_schema(string $lang, bool $use_fallback, array $values): void
    {
        $this->schema[] = $lang;
        if ($use_fallback) {
            foreach ($this->keys as &$key) {
                $key[] = $this->fallback;
            }
            $this->namespace_operation(function (&$key) {
                $key[] = $this->fallback;
            });
        }
        else {
            $total_count = 0;
            foreach ($this->namespaces as $nspace) {
                $total_count+=count($nspace);
            }
            if (count($values)==count($this->keys)+$total_count) {
                $i = 0;
                foreach ($this->keys as &$key) {
                    $key[] = $values[$i];
                    $i++;
                }
                $this->namespace_operation(function (&$key) use($values, $i){
                    $key[] = $values[$i];
                    $i++;
                });
            }
            else {
                throw new Exception('$values don\'t match keys number');
            }
        }
    }

    public function pop_from_schema(): void {
        array_pop($this->schema);
        foreach ($this->keys as &$key) {
            array_pop($key);
        }
        $this->namespace_operation(function($key) {
            array_pop($key);
        });
    }

    public function get_keys()
    {
        return $this->keys;
    }

    public function get_schema()
    {
        return $this->schema;
    }

    public function get_namespace(string $namespace) {
        return $this->namespaces[$namespace];
    }

    public function get_key_with_lang(string $key, string $lang): string
    {
        if (!in_array($lang, $this->schema)) {
            $lang = $this->schema[0];
        }
        if (!array_key_exists($key, $this->keys)) {
            throw new Exception("Key doesn't exists");
        }

        return $this->keys[$key][array_search($lang, $this->schema)];
    }

    public function add_key(string $namespace, string $key, array $values) : bool
    {
        $tokeys = false;
        if ($namespace=="")
        {
            $tokeys = true;
        }
        if (count($values)!=count($this->schema)) {
            throw new Exception("Number of values don't match schema");
        }
        if($tokeys) {
            try {
                $key = $this->get_key_with_lang($key, $this->schema[0]);
                return false;
            } catch (Exception) {
                $this->keys[$key] = $values;
            }
        } else {
            if (array_key_exists($key, $this->namespaces[$namespace])) {
                return false;
            }
            else {
                $this->namespaces[$namespace][$key] = $values;
            }
        }
        return true;
    }

}