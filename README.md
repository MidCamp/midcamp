# MidCamp
## The Drupal 8 website for midcamp.org
[![CircleCI](https://circleci.com/gh/MidCamp/midcamp.svg?style=shield)](https://circleci.com/gh/MidCamp/midcamp)
[![Code Climate](https://codeclimate.com/github/MidCamp/midcamp/badges/gpa.svg)](https://codeclimate.com/github/MidCamp/midcamp)

## Site Setup

###  Development Environment

This site uses the Amazee.io Drupal Docker development environment. If you already have this, you can skip this bit entirely. Their [documentation for setup](https://docs.amazee.io/local_docker_development/local_docker_development.html) is amazeballz, but if you just want to [quick get going on your Mac](https://stories.amazee.io/easy-local-drupal-development-on-os-x-a01a343f99e3):

```
brew tap amazeeio/cachalot
brew install docker docker-machine docker-compose cachalot
cachalot create --provider virtualbox
```

If you aren't running `virtualbox`, substitute your provider above. Valid values are: `'virtualbox', 'vmware', 'xhyve', or 'parallels'`.

You will be prompted to provide the passphrase for your ssh key. Comply.
Next, we need to make sure your shell knows about some Docker environment variables:

```
eval $(cachalot env)
```
So your shell remembers next time you log in, use one of these commands:
* Bash users, add it to your bash_profile with: `echo "eval \$(cachalot env)" >> ~/.bash_profile`
* Or for ZSH folks: `echo "eval \$(cachalot env)" >> ~/.zshrc`

### Getting Things Started

From inside the project root, run:
 * `composer install`
 * `docker-compose up -d`

1. Visit [midcamp.org.docker.amazee.io](http://midcamp.org.docker.amazee.io) in your browser of choice.

## How do I work on this?

From inside the project root, type `docker exec -itu drupal midcamp.org.docker.amazee.io bash`

This is your project directory; run `drush` commands from here. Avoid using git from here, but if you must, make sure you configure your name and email for proper attribution:

```
git config --global user.email 'me@palantir.net'
git config --global user.name 'My Name'
```

## How do I Drupal?

### The Drupal root

This project uses [Composer Installers](https://github.com/composer/installers), [DrupalScaffold](https://github.com/drupal-composer/drupal-scaffold), and [the-build](https://github.com/palantirnet/the-build) to assemble our Drupal root in `web`. Dig into `web` to find the both contrib Drupal code (installed by composer) and custom Drupal code (included in the git repository).

### Using drush

You can run `drush` commands from anywhere within the repository, as long as you are ssh'ed into the vagrant.

### Installing and reinstalling Drupal

```
drush si --sites-subdir=default --account-name="admin" --account-pass="admin" --y config_installer
```

### Adding modules

* Download modules with composer: `composer require drupal/bad_judgement:^8.1`
* Enable the module: `drush en bad_judgement`
* Export the config with the module enabled: `drush config-export`
* Commit the changes to `composer.json`, `composer.lock`, and `conf/drupal/config/core.extension.yml`. The module code itself will be excluded by the project's `.gitignore`.

### Patching modules

Sometimes we need to apply patches from the Drupal.org issue queues. These patches should be applied using composer using the [Composer Patches](https://github.com/cweagans/composer-patches) composer plugin.

## How do I run tests?

* `vendor/bin/behat`
* `vendor/bin/phpcs --standard=vendor/drupal/coder/coder_sniffer/Drupal features/bootstrap web/modules/custom`
* `vendor/bin/phpmd web/modules/custom text .phpmd.xml` 
  * This should be configured to show the same errors triggered by Code Climate that you see on the Pull Request.

## Troubleshooting

If you get the following error:
> Cannot connect to the Docker daemon. Is the docker daemon running?

Make sure your environment variables are set. `eval $(cachalot env)` Maybe you didn't add that to your bash_profile or zshrc as suggested?
