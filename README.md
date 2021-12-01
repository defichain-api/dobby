# DeFiChain DOBBY

Dobby is your personal house elf. He's always busy and watches your vaults on the DeFiChain.

You can setup as much vaults as you want, each with a custom amount of trigger points for notifications.

Dobby can inform you via mail, telegram message or a webhook. More notification gateways will be released in future.


## Setup own Dobby instance

### These are the requirements for Dobby:

- MariaDB/MySQL
- Redis
- PHP 8.0
- NPM & [Quasar](https://quasar.dev/quasar-cli/installation)
- Mailserver, if you want to receive notifications by mail
- Telegram Bot, if you want to receive notifications on telegram. Add the bot secret in the .env after the instance 
  setup. To receive the secret, be kind to the [BotFather](https://t.me/botfather).

### Follow these steps to setup your own instance of Dobby on an own server:

1. checkout the repo
2. install frontend dependencies with `npm install && cd web_app && yarn install`
7. create the webviews with `cd web_app && quasar build && cd .. && npm run production`
3. install php dependencies with `composer install`
4. create copy of the environment file `cp .env.example .env`, then add your redis/database credentials
5. create an app secret with `php artisan key:generate`
6. migrate the database with `php artisan migrate`

