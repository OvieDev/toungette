<?php

namespace OvieDev\Toungette;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ToungetteBlade extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        Blade::directive("toungette", function($data) {
            return '<?php 
                          $computed = trim('.$data.', " ");
                          $array = explode(";", $computed);
                          $scheme = $array[0];
                          $key = $array[1];
                          $t = new OvieDev\Toungette\Translator(resource_path("views/$scheme"), "", "pl");
                          $text = $t->scheme->get_key_with_lang($key, $t->lang);
                          echo $text;
                    ?>';
        });
    }
}
