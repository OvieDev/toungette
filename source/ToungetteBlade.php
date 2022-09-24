<?php

namespace OvieDev\Toungette;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ToungetteBlade extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        Blade::directive("toungette", function($data) {
            $computed = trim($data, " ");
            $array = explode(";", $computed);
            $scheme = $array[0];
            $key = $array[1];
            $t = new Translator(resource_path("views/$scheme"), "", "pl");
            return $t->scheme->get_key_with_lang($key, $t->lang);
        });
    }
}