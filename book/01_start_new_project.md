# How to start your own project

## Basic Strategy

* Clone boilerplate and change origin url to your own repository
* Set boilerplate repository as new remote url
* During development period, you should pull update from boilerplate, because it also be maintained continuously.
* After launching your service, you should not merge all update from boilerplate, because sometimes boilerplate introduced braking change and it should affect issues on your service. You can use cherry-pick some commit from boilerplate to import some new features.

## Step by step guide to create new repository.

* Clone boilerplate to local

`git clone git@github.com:takaaki-mizuno/laravel-boilerplate.git`

* Create new repository on remote ( e.g. GitHub )

* Set remote url to new your repository

`git remote set-url origin  git@github.com:organization/repository-name.git`

* Add boilerplate remote repository as another remote url.

`git remote add boilerplate git@github.com:takaaki-mizuno/laravel-boilerplate.git`

* You can get update of boiler plate with `git pull boilerplate master` command anytime you like.

* Use composer to install libraries.

`composer install`

Update command may cause issue on the first time.


