<?php


namespace OvieDev\Toungette;
use OvieDev\Toungette\Scheme;
use simplehtmldom\HtmlDocument;

class Translator
{
    private string $page_template;
    public Scheme $scheme;
    public string $lang;
    public string $text;
    private array $keys;

    public function __construct($template, $page, $lang=null) {
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
        $this->scheme = new Scheme($template, "Fallback text");
        $this->keys = $this->scheme->get_keys();
    }

    private function get_lang_index(): int
    {
        $index = 0;
        foreach ($this->scheme->get_schema() as $l) {
            if ($l==$this->lang) {
                return $index;
            }
            $index+=1;
        }
        return 0;
    }

    private function add_url_param($url): string
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if (!str_contains($query, "lang")) {
            if ($query) {
                $symbol = '&';
            } else {
                $symbol = '?';
            }
            $url .= $symbol . "lang=" . $this->lang;
        }
        return $url;
    }

    private function translate_links($page): string
    {
        $html = new HtmlDocument();
        $html->load($page);
        $a = $html->find("a[href]");
        foreach ($a as $link) {
            $url = $this->add_url_param($link->href);
            $link->href = $url;
        }
        return $html;

    }

    private function lang_block($page) : string {
        $html = new HtmlDocument();
        $html->load($page);
        $blocks = $html->find("langblock");
        foreach ($blocks as $block) {
            if (!$block->lang) {
                $html_block_text = $block->innerText;
                $block->outerText = $html_block_text;
            }
            else {
                if ($this->lang==$block->lang) {
                    $block->remove();
                }
                else {
                    $block->__set("outertext", $block->innerText());
                }
            }
        }
        return $html;
    }

    public function use_namespace(string $namespace) {
        $n = $this->scheme->get_namespace($namespace);
        foreach (array_keys($n) as $key) {
            $this->keys["$namespace.$key"] = $n[$key];
        }
    }
    // TODO: Test this function
    private function html_tag_handle($page) {
        $html = new HtmlDocument();
        $html->load($page);
        $tag = $html->find("condition[bool]");
        foreach ($tag as $tags) {
            eval('$code = '."$tags->href;");
            if ($code) {
                $tags->remove();
            }
        }
        return $html;
    }

    public function translate(): void
    {
        $html = file_get_contents($this->page_template);
        foreach (array_keys($this->keys) as $key) {
            $pattern = '/(?<!\/){'.$key.'}/';
            $html = preg_replace($pattern, $this->scheme->get_keys()[$key][$this->get_lang_index()], $html);
        }
        $html = preg_replace('/(?<=[\s\t\n])\//', '', $html);
        $html = $this->translate_links($html);
        $html = $this->lang_block($html);
        $this->text = $html;
    }

    public function fill(array $array): void {
        foreach ($array as $item) {
            $item = str_replace('@', '&#64;', $item);
            $this->text = preg_replace('/@fill/', $item, $this->text, 1);
        }
    }
}
