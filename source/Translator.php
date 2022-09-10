<?php

namespace toungette;

class Translator
{
    private $template_path;
    private $page_template;
    private $lang;

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
    }
}