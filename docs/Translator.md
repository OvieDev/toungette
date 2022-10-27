# `Translator` class
Translator is the basic class for Toungette. It translates pages using `schem.json` and `index.tounge` files 

### Properties
- `scheme` - contains [Scheme](Scheme.md) used for translating the
website *(Scheme)*
- `lang` - Language used by the translator *(string)*
- `text` - Stores translated text. By default, it's empty *(string)*

### `translate` method
Translates .tounge files by using keys defined in `schem.json`

### `fill` method
Fills the gaps made by \@fill directives

*Arguments* \
`$array` - array to fill the gaps

**NOTE:** Use this method after translating your page

## Constructor
*Arguments:* **string** *$template*, **string** *$page*, **string** *$lang=""* \
`$template` - Path to `schem.json` file \
`$page` - Path to .tounge file \
`$lang` - Language used to translate the website *(optional)*

