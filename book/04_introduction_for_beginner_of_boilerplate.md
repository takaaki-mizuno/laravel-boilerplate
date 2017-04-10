Introduction for Beginner of Laravel Boilerplate
=======
**Table of Content**

- [Introduction](#introduction)
    - [Purpose](#purpose)
- [Setup dev environments with Homestead](#setup-dev-environments-with-homestead)
    - [Software packages](#software-packages)
        - [Virtual box](#virtual-box)
        - [Vagrant](#vagrant)
        - [Git](#git)
    - [Install the Homestead Vagrant Box](#install-the-homestead-vagrant-box)
        - [In MacOS](#in-macos)
        - [In Windows](#in-windows)
    - [Install Homestead](#install-homestead)
        - [In MacOS](#in-macos-1)
        - [In Windows](#in-windows-1)
    - [Deploy PHP project with Laravel Boilerplate to Homestead](#deploy-php-project-with-laravel-boilerplate-to-homestead)
        - [Clone Laravel Boilerplate](#clone-laravel-boilerplate)
        - [Deploy Laravel Boilerplate to Homestead](#deploy-laravel-boilerplate-to-homestead)


## Introduction ##
Introduction for Beginner of Laravel Boilerplate

### Purpose ###

 - Setup Dev Environments with Homestead
 - Deploy a  PHP project with
[Laravel Boilerplate](https://github.com/takaaki-mizuno/laravel-boilerplate.git) to Homestead

## Setup dev environments with Homestead ##

(Reference: [https://laravel.com/docs/5.4/homestead](https://laravel.com/docs/5.4/homestead))

### Software packages ###
#### Virtual box ####

 - Latest version: 5.1.14
 - Website: [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads)
 - Download URL: [MacOS](http://download.virtualbox.org/virtualbox/5.1.14/VirtualBox-5.1.14-112924-OSX.dmg)

#### Vagrant ####

 - Latest version: 1.9.1
 - Website: [https://www.vagrantup.com/](https://www.vagrantup.com/)
 - Download URL: [MacOS](https://releases.hashicorp.com/vagrant/1.9.1/vagrant_1.9.1.dmg)

#### Git ####

 - Latest version: 2.12.0
 - Website: [https://git-scm.com/](https://git-scm.com/)
 - Download URL: [MacOS](https://git-scm.com/download/mac)

### Install the Homestead Vagrant Box ###

#### In MacOS ####

 - Open terminal.
 - Run command: `vagrant box add laravel/homestead`
 - Wait a few minutes until the download is complete

#### In Windows ####
_(TBD)_

### Install Homestead ###

#### In MacOS ####

 - Open terminal.
 - The first you need generate a RSA key and .shh folder will be create:
 ```bash
 $ ssh-keygen -t rsa
 ```
 - Choice whatever folder you want to store Homestead folder. In this guideline, we can use home folder of Mac and then clone Homestead from GitHub:
 ```bash
 $ cd ~
 $ git clone https://github.com/laravel/homestead.git Homestead
 ```
 - Once you have cloned the **Homestead** repository, run commands below and .homestead folder created
 ```bash
 $ cd Homestead/
 $ bash init.sh
 ```
 - Update file **Homestead.yaml** follow the sample (**HOMESTEAD_SOURCE_CODE_FOLDER** is the path you want to store source code):

```yaml
  ---
  ip: "192.168.10.10"
  memory: 2048
  cpus: 1
  provider: virtualbox

  authorize: ~/.ssh/id_rsa.pub

  keys:
      - ~/.ssh/id_rsa

  folders:
      - map: HOMESTEAD_SOURCE_CODE_FOLDER
          to: /home/vagrant/Code

  sites:
      - map: homestead.app
          to: /home/vagrant/Code
      - map: laravel-boilerplate.app
          to: /home/vagrant/Code/laravel- boilerplate/public


  databases:
      - homestead
```

  - Add hosts file (/etc/hosts) 2 lines below
  
  ```
  192.168.10.10  homestead.app
  192.168.10.10  laravel-boilerplate.app
  ```

  - Go to Homestead folder and then run command below to start Homestead

  ```bash
  vagrant up
  ```

  - Go to **HOMESTEAD_SOURCE_CODE_FOLDER**, create **info.php** with content below

  - Open your browser, type [http://homestead.app/info.php](http://homestead.app/info.php) you can see phpinfo screen.

#### In Windows ####
_(TBD)_

### Deploy PHP project with Laravel Boilerplate to Homestead ###

#### Clone Laravel Boilerplate ####

- Go to **HOMESTEAD_SOURCE_CODE_FOLDER**. Clone Laravel Boilderplate GitHub repository with command below:

```bash
$ git clone https://github.com/takaaki-mizuno/laravel-boilerplate.git laravel-boilerplate
```

#### Deploy Laravel Boilerplate to Homestead ####
- Go back to Homestead folder and we need to SSH to Homestead server. Run command below
```bash
$ vagrant ssh
```
- Go to source code folder in **/home/vagrant/Code/** and you can see laravel-boilerplate folder
```bash
$ cd /home/vagrant/Code
```
- Go to laravel-boilerplate folder, run command below to install libraries
```bash
$ composer install
```

- Once you have installed libraries, you need create .env file for project
```bash
$ cp .env.example .env
$ php artisan key:generate
```
- Next, update database info in **.env** file
(Note: APP_KEY will be automated to generate with the command above)

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:eF5QXxO9kHoB+Y4hzy5Uajzv09oeSQdJD5Gbeq9NdHI=


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

MAIL_FROM_ADDRESS=info@example.com
MAIL_FROM_NAME=Info

FACEBOOK_APP_ID=
FACEBOOK_SECRET=

SLACK_WEB_HOOK_URL=

AWS_IMAGE_REGION=ap-northeast-1
AWS_KEY=YOUR_KEY
AWS_SECRET=YOUR_SECRET
AWS_SES_REGION=us-east-1
AWS_IMAGE_BUCKET=

OFFLINE_MODE=false
```

- Back to browser, go to [http://laravel-boilerplate.app/admin/](http://laravel-boilerplate.app) and then you can see home page of boilerplate.
- Go to [http://laravel-boilerplate.app/admin/](http://laravel-boilerplate.app/admin/) and try login with username: **test@example.com** and password: **test**
