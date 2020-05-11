
[![CircleCI](https://circleci.com/gh/opendialogai/opendialog/tree/master.svg?style=svg&circle-token=aefbfc509382266413d6667a1aef451c7bf82f22)](https://circleci.com/gh/opendialogai/opendialog/tree/master)

# OpenDialog - open-source conversational applications management system 

OpenDialog enables you to quickly build conversational applications. 

It provides a web widget that can be styled to specific needs and can be embedded on any website. 

You write conversational applications using OpenDialog's flexible conversational language, and define the messages that your bot will send the user through OpenDialog's message markup language. 

For all the details of how OpenDialog helps you build sophisticated chatbots visit our [documentation site](https://docs.opendialog.ai).

# Trying out OpenDialog

If you want to see OpenDialog in action you can try out the latest version through our automatically produced Docker image. 

As long as you have Docker installed on your local machine you can do:
- `cd docker/od-demo`
- `docker-compose up -d app`
- `docker-compose exec app bash docker/od-demo/update-docker.sh`

You can then visit http://localhost and login to OpenDialog with admin@example.com / opendialog


## Quickstart

This will get you up and running with minimal manual configuration.

* [Install Lando](https://docs.devwithlando.io/installation/system-requirements.html) -- [Lando](https://github.com/lando/lando) is a wrapper around Docker services and it brings together everything that is required for OpenDialog.
 
* Run the setup script: `bash ./scripts/set_up_od.sh {appname}` where {appname} is the name of the app
On initial setup you will be prompted to clear your local dgraph and conversations. Select `yes`.
* Your app will be available at: https://{appname}.lndo.site/admin
    * You may need to permanently trust the ssl cert
    * You can find this at `~/.lando/certs/lndo.site.crt`
* Log in using default credentials `admin@example.com` and `opendialog`
* Go to > Test Bot
    * Ask the Bot anything.
    * You should see the no-match message.
* The DGraph browser will be available here: https://dgraph-ratel.lndo.site
* DGraph Alpha should be available at https://dgraph-alpha.lndo.site

## Manual Configuration

### Front end set up

After running `composer install` or `composer update`, an update script file should be moved to the root of your project
directory. Run this script to set up the OpenDialogAI-Webchat and OpenDialogAI-Core packages with

```bash update-web-chat.sh -i```

#### Options

The following options are available on the script:

+ `-h` Get help
+ `-p` Set if this is to be run in the production environment
+ `-l` Set if you are using Lando for local development. Will run the commands from within Lando
+ `-i` Set if you need to install the node dependencies. This defaults to false, so you should always set this for the fist run
+ `-f` Whether to force updating by deleting local dependencies. If set, will remove the vue-beautiful-chat node module before reinstalling 

Run this script every time an underlying package is updated.

#### Webchat Configuration 

The webchat configuration can be found in the `webchat_settings` table. The config table should be seeded by running:

```php artisan webchat:setup```

This will set up the `webchat_settings` table with all the requried values.
For this to work successfully, the `APP_URL` environment variable need to be set

#### DGraph configuration

Add (and edit as necessary) the following lines to your .env file to let OD know where to find your DGraph installation:
```
DGRAPH_URL=http://dgraph-alpha
DGRAPH_PORT=8080
```

(`http://dgraph-alpha` is the internally resolvable hostname for DGraph in the lando set up)

#### Config

Publish the opendialog config by running:

```php artisan vendor:publish --tag=opendialog-config```

This will copy over all required config files to `config/opendialog` for you to add you own values


## Conversations

Conversations in OpenDialog are managed in the mysql database, and published to DGraph when they are ready to be used.

There are 2 scripts included with this application that allow you to import and export conversations that can be checked into 
the repo and shared

### Configuration

There is a config file `opendialog/active_conversations.php` in the config directory. This contains a list of all conversation
names that should be exported / imported. This list is used by both scripts and should be kept up to date with your local conversations.
Just the conversation name is needed.

### Import Conversations

To import all conversations, run

```php artisan conversations:setup```

This will import all conversations that are listed in `opendialog/active_conversations.php` and exist in `resources/conversartions`

#### Example Conversations

By default, the welcome and no match conversations are included with OpenDialog. Running the script will create a no match
and a welcome conversation (but without the required messages)

### Exporting Conversations

Run:

```php artisan conversations:export```

To export all conversations in the `opendialog/active_conversations.php` config file. This will dump the current conversation
YAML and all related outgoing intents and message templates



## Local Package Development

The `packages:install` artisan command will checkout and symlink `opendialog-core` and / or `opendialog-webchat` to a `vendor-local` directory.

To install dependencies using it, you can run `artisan packages:install`. You will be asked if you want to use local versions of core and webchat.
If so, you can now use, edit and version control these repositories directly from your `vendor-local` directory.

After doing so, you may need to run `php artisan package:discover` to pick up any new modules.

Note:
Before a final commit for a feature / fix, please be sure to run `composer update` to update the `composer-lock.json` file so that it can be tested and deployed with all composer changes in place

### Reverting

To revert back to the dependencies defined in `composer.json`, run the `artisan packages:install` command again and answer no to installing core and webchat locally.

## Testing

The project is set up to run all commits through (CircleCI)[https://circleci.com], which runs tests and checks for code 
standards.

To run the test suite locally through Lando, run 

    lando test

Information on setting up phpstorm to run tests on the (OpenDialog Wiki)[https://github.com/opendialogai/opendialog/wiki/Running-tests-through-PHPStorm]

## Running Code Sniffer
To run code sniffer, run the following command
```./vendor/bin/phpcs --standard=od-cs-ruleset.xml app/ --ignore=*/migrations/*,*/tests/*```

## Git Hooks

To set up the included git pre-commit hook, first make sure the pre-commit script is executable by running

```chmod +x .githooks/pre-commit```

Then configure your local git to use this directory for git hooks by running:

```git config core.hooksPath .githooks/```

Now every commit you make will trigger php codesniffer to run. If there is a problem with the formatting
of the code, the script will echo the output of php codesniffer. If there are no issues, the commit will
go into git.
