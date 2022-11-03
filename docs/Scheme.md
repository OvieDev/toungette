# `Scheme` class
Scheme is used as a representation of `schem.json` in PHP code.

### Properties
- `$fallback` - Value that's used instead of an array in `push_to_schema` method
- `$default_lang` - Language that's used, when your site doesn't handle
some languages
- `$json` - Raw json of scheme

### `reset_scheme` method
Reset current scheme to original state (from json)

### `modify_key` method
*Arguments:* **string** *$keyname*, **array** *$values*\
Modifies one key with a value (key must not be in a namespace)

`$keyname` - Key that will be modified \
`$values` - What values should be modified

**NOTE: `$values` must be the same length as `schema` in your `schem.json`**

### `delete_key` method
*Arguments:* **string** *$keyname*, **string** *$namespace=""* \
Deletes a key from scheme. If namespace argument is specified, it would
delete key from a namespace

`$keyname` - Key that will be deleted \
`$namespace` - Namespace, where key will be deleted. If it stays empty,
then it would delete key from main key collection instead *(optional)*

### `push_to_schema` method
*Arguments:* **string** *$lang*, **bool** *$use_fallback*, **array** *$values*\
Adds a language to schema and updates all the keys (including namespaces). Number of
entries in `$values` array must be equal to the number of keys.

`$lang` - Name of the language that will be added (ex. fr (French), de (German ) etc.)\
`$use_fallback` - Instead of `$values`, use `$fallback` variable to add keys \
`$values` - Values that will be added to keys. If we have 3 keys, then first key will add
first value from the array, second key second value etc.

### `pop_from_schema` method
Pops the last language out of the schema and last value of all keys

### `get_keys` method
Gets array of main key collection (aka `keys` in `schem.json`)

### `get_schema` method
Gets array of languages (aka `schema` in `schem.json`)

### `get_namespace` method
*Arguments:* **string** *$namespace*\
Gets a selected namespace with all of its keys

`$namespace` - Namespace to select

### `get_key_with_lang` method
*Arguments:* **string** *$key*, **string** *$lang*\
Selects a value of a key paired to selected language

`$key` - Key to be selected\
`$lang` - Language paired with a value of a key

### `add_key` method
*Returns:* **bool** (true if key got created, false on error)\
*Arguments:* **string** *$namespace*, **string** *$key*, **array** *$values*\
Creates a new key. If namespace wasn't specified, it will create it in
main key collection

`$namespace` - Namespace, where key should be added. If not specified, it will add it
to main key collection \
`$key` - Name of the created key\
`$values` - Values, that should be put inside a key, that match with schema

## Constructor
*Arguments: $file, $fallback* \
`$file` - Location of `schem.json` \
`$fallback` - Value, that's used instead of the array in `push_to_schema` method