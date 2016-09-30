# Laravel Boilerplate

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/takaaki-mizuno/laravel-boilerplate/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/takaaki-mizuno/laravel-boilerplate/?branch=master)

[![CircleCI](https://circleci.com/gh/takaaki-mizuno/laravel-boilerplate.svg?style=svg)](https://circleci.com/gh/takaaki-mizuno/laravel-boilerplate)

This is starter application template for Laravel.
Any suggestions, feedback, or push requests are really welcome.

## How To Start Your Project

After you clone this repository, you can change origin url to your own remote repository.

`git remote set-url origin  XXXXX`

And after that, you can add this boilerplate remote repository as another remote url.

`git remote add boilerplate git@github.com:takaaki-mizuno/laravel-boilerplate.git`

With that, you can get update of boiler plate with `git pull boilerplate master` command.

## Use Generators

On this boilerplate, I added many generators

Use `make:new-model` instead of `make:model` and use `make:repository`, `make:service`,`make:helper` to create repositories, services, helpers.
And `make:admin-crud` to create admin crud.

The process for setting up the base structure will be following.

1. You can create migration with `make:migration` and create the tables
2. Create model with `make:new-model`
3. Create repository with `make:repository`
4. Create Admin CRUD with `make:admin-crud`
5. Create services and helpers with `make:service` and `make:helper` if needed.

These generators create test code also. You need to add more tests on these files.
