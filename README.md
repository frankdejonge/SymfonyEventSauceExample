# Symfony EventSauce Example Project

In this example project we're going to be modelling a application flow
that's broken up into a couple stages:

1. A user applies for an account.
2. The admin receives an email.
3. An admin approves or declines the application in a dashboard.
4. The applicant receives an email.

## What's in the demo:

This demo features some of the more complex setups eventsauce has to offer.
It uses sync and async projections, uses RabbitMQ for async message consumers,
uses Doctrine for the message repository and has a complete set of tests.

## Running the example

```bash
$ composer install
$ docker-compose -f docker-compose.yml up
$ bin/console doctrine:migrations:migrate -n
$ bin/console rabbitmq:setup-fabric
$ bin/console server:start
$ bin/console rabbitmq:consumer application_process -w
```

Now your application is up and running open the application at: http://127.0.0.1:8000