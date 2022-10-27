# 5. Laravel + Toungette
You heard about this little framework called Laravel.
You tried it, and it was good. You now use Blade templates and
using `.tounge` files isn't really an option now. As we mentioned
before `.tounge` files are just HTML with translations...
\
**Now, how can we use Toungette with Laravel?**

## Use `@toungette` blade directive
We can integrate Toungette with Laravel by its custom
Blade directive. Syntax looks like this: \
`@toungette('scheme.json;key.name')` \
Before you do that though, you need to add `ToungetteBlade` as a service provider to your project.
It's very important to keep this syntax as it is because
otherwise it will not work. \
[< Back](filldirectives.md)