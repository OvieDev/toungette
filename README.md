# Toungette

![code cov](https://img.shields.io/codeclimate/maintainability/OvieDev/toungette?logo=codeclimate)
![code cov](https://img.shields.io/github/last-commit/OvieDev/toungette?logo=github)
![code cov](https://img.shields.io/badge/PHP-8.1-blueviolet)\
*Small and simple PHP library for translating websites*

## Introduction
Toungette is a PHP library used to simplify translating your website.
You only need 3 files to make it work, and you still have full control over
your translations without any need to create the same page twice, but with different
text.

## Installation
`composer require oviedev/toungette`

## Documentation
You can view documentation here: [Documentation](docs/introduction.md)

## How to use
Let's make a simple website with one page.\
Setup 3 files. `schem.json`, `index.tounge` and `index.php`.\
\
`schem.json` is where you define your translations. At the beggining of the file, you need to define `schema` property.
It's an array of avaible languages.\
Next you have the `keys` object. It stores your translations as arrays. Values of the keys are ordered via `schema`.
So let's define two keys. `key.hello` and `key.goodbye`

```json
{
  "schema": ["en", "es", "pl"],
  "keys": {
    "key.hello": [
      "Hello",
      "Hola",
      "Hej"
    ],
    "key.goodbye": [
      "Goodbye",
      "Adios",
      "Do widzenia"
    ]
  }
}
```
\
Let's move to the `index.tounge`. This is where you define, how your page will look. It's plain html, but you start with the `<body>` tag, instead of `<html>`. So our file may look like this:
```html
<body>
  <b>{key.hello}</b>
  <br />
  this text isn't affected by translations
  <br />
  {key.goodbye}
</body>
```
Notice that our keys are in curly braces.
\
\
Now, it's time for our `index.php` file. This is where the translation magic comes in. It contains every standard tag required for the website (`<html>` and `<head>`), excluding `<body>`

```php
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>My Toungette Page</title>
  </head>
  <?php
    require_once 'vendor/autoload.php';
    use OvieDev\Toungette\Translator;
    
    $t = new Translator('schem.json', 'index.tounge');
    $t->translate();
    echo $t->text;
  ?>
</html>
```
In that `<?php` tag we create a `Translator` object and we pass here our `schem.json` and `index.tounge` as parameters. Then we call `translate()` method and we echo it out. \
\
Now go to enter your website's url and add parameter `lang=en`, `lang=es` or `lang=pl`. This should be the result:

![English Translation](https://i.imgur.com/fahum09.jpg)
![Spanish Translations](https://i.imgur.com/xLrWIJH.jpg)
![Polish Translations](https://i.imgur.com/sJNITLm.jpg)
\
\
**Congratulations, you've made your first website with Toungette**
