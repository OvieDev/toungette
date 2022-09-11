<?php

namespace toungette;

use Exception;

class Translator
{
    private $template_path;
    private $page_template;
    private $json_template;
    public $lang;
    public $langs;

    public function __construct($template, $page, $lang=null) {
        $this->template_path = $template;
        $this->page_template = $page;
        if (isset($lang)) {
            $this->lang = $lang;
            if (isset($_GET['lang'])) {
                $this->lang = substr($_GET['lang'], 0, 2);
            }
        }
        else {
            $this->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        $this->parse_lang_template();
    }

    public function parse_lang_template() {
        try {
            $json_file = fopen($this->template_path, "r");
            $json_raw = fread($json_file, filesize($this->template_path));
            fclose($json_file);
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

    //TODO: translate function
    public function translate() {

    }
}