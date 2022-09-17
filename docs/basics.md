# 3. Basics of Translation in Toungette
Toungette needs 3 files to work. A language scheme, page template,
and main page. (`schem.json`, `index.tounge` and `index.php` accordingly)
## Language Scheme
A language scheme (or just scheme) defines translations and
the mapping between translation and language. A sample scheme
has 2 parameters - `schema` and `keys`.\
`schema` is an array of languages. It defines the order of
translations in keys. So for example when we have this schema:
```json 
"schema": ["en", "es", "pl"]
```
the key would look like this:
```json
"key.hello": [
        "Hello" 
        "Hola",
        "Hej"
]
```
Notice that first key is in English, second is in Spanish and
third is in Polish. Just like the scheme says.\
`keys` object defines the actual translations. Each key is an
array of text (as shown above) and it must have the same amount of
elements as `schema`. If it doesn't, Toungette throws an Exception.
You can have how many schemes you want.\
Full sample `schem.json`:
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
## Page Template
Page template defines the body of the website and only the body.
It's stored in our `index.tounge` file. Basically it's just plain html,
without `<!DOCTYPE HTML>` `html` and `<head>`. Only `<body>`.
In `.tounge`, besides normal text and html, we can write our keys.
When we want to do that, we wrap the name of our key in curly braces
(`{key.hello}` for example). Any text, which isn't in curly braces, will
not be translated.\
So let's look at sample `.tounge` file:
```html
<body>
    <b>{key.hello}</b>
    <br/>
    this text will be untouched
    <br/>
    {key.goodbye}
</body>
```
## Main Page 
Our main page is where we do actual code magic. It's our `index.php`
file in this case. We do the opposite to the page template here. We define
everything except `<body>`. Instead we write this:

```php
<?php
    require_once 'vendor/autoload.php';
    
    $t = new Translator("schem.json", "index.tounge");
    $t->translate();
    echo $t->text;
?>
```
We basically create a new translator, which uses our `scheme.json` as
language scheme and `index.tounge` as a page template. Then we
tell him to translate our template using keys from scheme and then
we echo out the result.\
Full code of `index.php`:

```php
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>My Page</title>
    </head>
    <?php
        require_once 'vendor/autoload.php';
    
        $t = new Translator("schem.json", "index.tounge");
        $t->translate();
        echo $t->text;
    ?>
</html>
```
[< Back](gettingstarted.md) | [Next >](filldirectives.md)