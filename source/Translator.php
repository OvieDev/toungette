<?php


namespace toungette;
require_once '..\vendor\autoload.php';
use Exception;

class Translator
{
    private string $template_path;
    private string $page_template;
    private array $json_template;
    public string $lang;
    private array $langs;
    public string $text;

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

    public function parse_lang_template(): void
    {
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

    private function get_lang_index(): int
    {
        $index = 0;
        foreach ($this->langs as $l) {
            if ($l==$this->lang) {
                return $index;
            }
            $index+=1;
        }
        return 0;
    }

    private function translate_links($page): string
    {
        $html = str_get_html($page);
        $a = $html->find("a[href]");
        foreach ($a as $link) {
            $url = $link->href;
            $query = parse_url($url, PHP_URL_QUERY);
            if (!str_contains($query, "lang")) {
                if ($query) {
                    $symbol = '&';
                } else {
                    $symbol = '?';
                }
                $url .= $symbol."lang=".$this->lang;
            }
            $link->href = $url;
        }
        return (string)$html;

    }

    public function translate(): void
    {
        $html = file_get_contents($this->page_template);
        foreach (array_keys($this->json_template['keys']) as $key) {
            $pattern = '/(?<!\/){'.$key.'}/';
            $html = preg_replace($pattern, $this->json_template['keys'][$key][$this->get_lang_index()], $html);
        }
        $html = preg_replace('/(?<=[\s\t\n])\//', '', $html);
        $html = $this->translate_links($html);
        $this->text = $html;
    }

    public function fill(array $array): void {
        foreach ($array as $item) {
            $item = str_replace('@', '&#64;', $item);
            $this->text = preg_replace('/@fill/', $item, $this->text, 1);
        }
    }
}