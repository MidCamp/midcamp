# MidCamp
## The Drupal 8 website for midcamp.org
[![CircleCI](https://circleci.com/gh/MidCamp/midcamp.svg?style=shield)](https://circleci.com/gh/MidCamp/midcamp)
[![Code Climate](https://codeclimate.com/github/MidCamp/midcamp/badges/gpa.svg)](https://codeclimate.com/github/MidCamp/midcamp)

## Site Setup

###  Development Environment

This site uses the Amazee.io Drupal Docker development environment. If you already have this, you can skip this bit entirely. Their [documentation for setup](https://docs.amazee.io/local_docker_development/local_docker_development.html) is amazeballz, but if you just want to quickly get going on your Mac:

- [Install pygmy](https://docs.amazee.io/local_docker_development/pygmy.html) with `gem install pygmy`
- Start pygmy with `pygmy up`

You will be prompted to provide the passphrase for your ssh key. Comply.


### Getting Things Started

From inside the project root, run:

- `composer install`
- `npm ci`
- `docker-compose up -d --build`

Visit [midcamp.org.docker.amazee.io](http://midcamp.org.docker.amazee.io) in your browser of choice.  At this point you should see a Drupal installation page.  See below for Drush commands to install the site.  

## How do I work on this?

From inside the project root, type `docker-compose exec cli bash`

This is your project directory; run `drush` commands from here. Avoid using git from here, but if you must, make sure you configure your name and email for proper attribution:

```
git config --global user.email 'me@palantir.net'
git config --global user.name 'My Name'
```

## How do I Drupal?

### The Drupal root

This project uses [Composer Installers](https://github.com/composer/installers), [DrupalScaffold](https://github.com/drupal-composer/drupal-scaffold), and [the-build](https://github.com/palantirnet/the-build) to assemble our Drupal root in `web`. Dig into `web` to find the both contrib Drupal code (installed by composer) and custom Drupal code (included in the git repository).

### Using drush

You can run `drush` commands from anywhere within the repository, as long as you are ssh'ed into the container.

### Syncing your local environment

To get up and running locally, the easiest thing to do is update from the production database with:

```bash
drush sql-sync @production @self
```

If you want the files too, use:

```bash
drush rsync @production:%files @self:%files
```

### Adding modules

* Download modules with composer: `composer require drupal/bad_judgement:^8.1`
* Enable the module: `drush en bad_judgement`
* Export the config with the module enabled: `drush config-export`
* Commit the changes to `composer.json`, `composer.lock`, and `conf/drupal/config/core.extension.yml`. The module code itself will be excluded by the project's `.gitignore`.

### Working on the theme

As of 2019, component markup, styles and interactivity all live in the [Hatter Styleguide](https://github.com/MidCamp/hatter-v2)
The majority of theming work will occur in that repository and instructions are included in the [readme](https://github.com/MidCamp/hatter-v2/blob/master/README.md). 

The compiled assets are packaged as an npm dependency. To install the latest, run `npm install` either in the root of this
repository, or the theme directory. The initial setup process, along with the Circle CI build process will install these
dependencies as well.

To include a specific version of the Hatter styleguide, update the version of the hatter dependency in `package.json` in
the related theme and them run `npm install`

### Patching modules

Sometimes we need to apply patches from the Drupal.org issue queues. These patches should be applied using composer using the [Composer Patches](https://github.com/cweagans/composer-patches) composer plugin.

## How do I run tests?

* `vendor/bin/behat`
* `vendor/bin/phpcs --standard=vendor/drupal/coder/coder_sniffer/Drupal features/bootstrap web/modules/custom`
* `vendor/bin/phpmd web/modules/custom text .phpmd.xml`
  * This should be configured to show the same errors triggered by Code Climate that you see on the Pull Request.

## Troubleshooting

### Cannot connect to Docker daemon
If you get the following error from `pygmy up`:
> Cannot connect to the Docker daemon. Is the docker daemon running?

Make sure you have the Docker installed and running.

### Failed to start haproxy
If you get an error like this while running `pygmy up`:
> Error response from daemon: driver failed programming external connectivity on endpoint amazeeio-haproxy (2c5ed4b1999a8e5d586ca50f666e1ec0db012265375c60d780551b7368c351d0): Error starting userland proxy: Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE)
> Error: failed to start containers: amazeeio-haproxy

Apache is probably already running on port 80 for your system. Run `sudo apachectl stop` and try again.

If this does not work, run `lsof -PiTCP -sTCP:LISTEN` and see what is using port 80. Kill it.

### I can't see new things in the style guide

Are you sure your changes got merged to [Hatter's](https://github.com/MidCamp/hatter-v2) `master`?