# How to develop your service on this boilerplate


## Model

Model represent the data entity. Laravel itself provides generator command for model ( `php artisan make:model` ). But boilerplate provides alternative command ( `php artisan make:new-model` ) and you should use it.

## Presenter

Model can have presenter. You can read the following article

[https://laravel-news.com/2014/03/clean-up-your-laravel-models-with-view-presenters/]

Model itself should not have any function to convert data for presentations.

## Repository

Repositories provide interfaces for accessing models. Each model should have one repository. For example, `User` model should have `UserRepository` . 
You should use generator command ( `php artisan make:repository` )

You can understand repository more with the following article.

[https://bosnadev.com/2015/03/07/using-repository-pattern-in-laravel-5/]

But we don't use paginate because we use offset / limit instead of perPage/page.

### Naming rule for repository methods

* `find` means get one model specified conditions. `findByUserId` means get one model by user_id ( It also means the model can unique with user_id )

* `all` means get all data at once. `allByCategoryId` fetch all data which has specified category_id.

* `get` means get data by using paging ( offset and limit ). 

* `count` means count number of data and returns integer value.

* `delete` means delete data

* `create` means create new model

* `update` means update model with given data

* `save` means save current model to storage.

Repository should not include other than the following methods and if you need to create other methods, you need to consider using services.

Boilerplate repository has several auto method generation features. So if your model have column named `category_id` amd `is_enabled`, you can automatically use `allByCategoryId`, `getByCategoryIdAndIsEnabled`. It is same as Model auto generated methods provided by Laravel.

Repository should only work with single models. If you need to handle 2 or more models ( such as User and Category ). Should use service instead of repository.

### Controller

Controller should only have the following responsibilities.

* Validation
* Parameter extraction from request object
* Call service/repository methods
* Generate response.

Controller never do any business logic related actions. Such as sign in, create new models, and so on. You should write service method and give descriptive name and call it from controllers.

And Controller should only have route action public functions. Must not have any private functions. You should use service instead of controller private methods.


### Service

Services provide business logic. Services can call repositories but repositories cannot call services. Never call service from Models/Repositories.


### Helpers

Helpers is for utility functions which can be called from anywhare includes views, models, repositories, services.
