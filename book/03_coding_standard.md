# Other Coding Standards

Some other at random coding standard rules.

## Use action function to generate url

Never use actual paths such as `<a href="/users">`. Use `<a href="{{ action('User\UserController@index') }}">`
It may give you flexibility to change url later.

## Use alias functions such as `action`, `config`, `trans` and so on.

On laravel, there are many pre-defined alias functions. `action` is alias of `\URL::action`, `config` os alias of `\Config::get` to make code simple and short, you should use alias functions.

## Never do just copy & paste

If you find some solution in web pages such as stack overflow, you should not just copy & paste. You need to update to follow our coding standard and also wirte url as comment for reference.

## Update with php-cs-fixer

This repository already have `.php-cs` file. You can run php-cs-fixer to fix your code format.

```
php-cs-fixer fix -v app
```
