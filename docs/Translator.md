# `Translator` class
Translator is the basic class for Toungette. It translates pages using `schem.json` and `index.tounge` files 

### `translate` method
Translates text

### `fill` method
Fills the gaps made by \@fill directives

*Arguments*
`$array` - array to fill the gaps

**NOTE:** Use this method after translating your page

## Constructor
*Arguments: $template, $page, $lang=""*
$template - path to `schem.json` file
$page - path to `index.tounge` file
$lang - what language translator should use
