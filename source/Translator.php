<?php


namespace toungette;

use Exception;

class Translator
{
    private readonly string $template_path;
    private readonly string $page_template;
    private readonly array $json_template;
    public string $lang;
    public readonly array $langs;

    public function __construct($template, $page, $lang=null) {
        $this->template_path = $template;
        $this->page_template = $page;
        if (isset($_GET['lang'])) {
            $this->lang = $_GET['lang'];
        }
        elseif (isset($lang)) {
            $this->lang = $lang;
        }
        else {
            $this->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        $this->parse_lang_template();
    }

    public function parse_lang_template() {
        try {
            $json_raw = file_get_contents($this->template_path);
            if (!$json_raw) {
                throw new Exception("Couldn't find file or open it");
            }
            $json = json_decode($json_raw, true);
            if (!isset($json)) {
                throw new Exception("Invalid JSON");
            }
            $this->json_template = $json;
            $this->langs = $this->json_template['schema'];
            foreach ($this->json_template['keys'] as $key) {
                if (count($key)!=count($this->langs)) {
                    throw new Exception("Key breaks schema");
                }
            }

        }
        catch (Exception $e) {
            die("Cannot parse JSON: $e");
        }

    }

    private function getLangIndex() {
        $index = 0;
        foreach ($this->langs as $l) {
            if ($l==$this->lang) {
                return $index;
            }
            $index+=1;
        }
        return 0;
    }

    public function translate() {
        $html = file_get_contents($this->page_template);
        foreach (array_keys($this->json_template['keys']) as $key) {
            $pattern = '/(?<!\/){'.$key.'}/';
            $html = preg_replace($pattern, $this->json_template['keys'][$key][$this->getLangIndex()], $html);
        }
        $html = preg_replace('/(?<=[\s\t\n])\//', '', $html);
        echo $html;
    }
}