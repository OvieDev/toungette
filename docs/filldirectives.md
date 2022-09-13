# 4. \@fill Directives
Since `.tounge` files are basically HTML but on steroids, it doesn't
have options to use PHP. Plain HTML, nothing else.\
But sometimes, you need to use a value from PHP, maybe a username,
or a profile info. What should we do now? Well, don't panic because we have @fill
directives.
## What are \@fill directives.
@fill directives can be thought of as gaps to enter words in those
children's books. We leave a blank spot and we can fill it later.
We implement them in page templates, just like this:
```html
<body>
    <b>{key.hello}</b>
    <br/>
    random number of the day: @fill
    <!--this would be filled-->
</body>
```
## Filling the gaps
Now, we want to fill that gap with a random number. So, when we have
our `index.php` file:
```php
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>My Page</title>
    </head>
    <?php
        require_once 'vendor/autoload.php';
        use toungette\Translator;
    
        $t = new Translator("schem.json", "index.tounge");
        $t->translate();
        echo $t->text;
    ?>
</html>
```
We would add after `$t->translate()` a new method. `$t->fill()`\
`fill()` accepts an array as an argument. Every @fill would be replaced
with an array element. First @fill will be filled with index 0, second with 1 etc.
\
So let's transform our file to do our fills:
```php
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>My Page</title>
    </head>
    <?php
        require_once 'vendor/autoload.php';
        use toungette\Translator;
    
        $t = new Translator("schem.json", "index.tounge");
        $t->translate();
        $t->fill([strval(random_int(0, 100))]);
        echo $t->text;
    ?>
</html>
```
**NOTE:** It's very important to do our fills after translation and before echoing
the result.

That's the result:\
![@fill in action](https://i.imgur.com/Aeudm3x.jpg)

**TIP:** When you have a lot of fills to do, your `index.php` can
become pretty bloated. You can create another file, which contains
functions. In that way, your main file is not so bloated and you have
everything organized.