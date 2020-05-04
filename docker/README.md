# ⚙️ Setting up the development environment

The files in this directory will help you deploy Laravel applications in development
environments from version 5.8 to the latest version available just by changing a line.

## The context

To get a little into context, we are testing a simple application that handles
three related data entities. A data model called `Author`, can publish articles
in a data model called `Article` likewise, these articles can have comments that
are manipulated by a data model called `Comment`.

Next, the file structure and its role in the development environment will be explained.

## Directory structure

The directory structure is no different from the typical structure of a Laravel
project. All files added to these folders will be added to the container at build
time and their modification will be added at run time.

## `docker-compose.override.example.yml` file

You can rename this file to `docker-compose.override.yml` and add your custom
environment vars. For example, if you want to **change the Laravel version**, only
add this value in the `docker-compose.override.yml` file.

```yml
# docker-compose.override.yml
version: "3.4"

services:
  fpm:
    environment:
      APP_VERSION: 6
```

## `.env` file

It is important not to confuse the .env file in this directory with the Laravel
environment configuration file. Rather it is a combination of both, in the first
section of the file environment variables are defined that the initial container
script will use to configure the Laravel application.

For example, you can define the version number of Laravel you want to test, the
folder where these files will be stored and other documented configurations.

## `composer.json` file

The `composer.json` file in this directory is nothing more than an extension to the
final `composer.json` file of your Laravel application. The startup script did a
combination of both before lifting the container.

## Setup

1. Run the docker containers

```bash
$ cd docker/
$ docker-compose up -d
```

2. Wait 20 seconds and run the migrations

```bash
# Replace ${APP_VERSION} with your version number especifed in .env file
$ docker exec -it fpm php apps/${APP_VERSION}/artisan migrate:fresh --seed
```

All done, start to make request to `http://localhost:8080/api/restql`.

