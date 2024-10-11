# MidCamp

## The Drupal website for midcamp.org
[![CircleCI](https://circleci.com/gh/MidCamp/midcamp.svg?style=shield)](https://circleci.com/gh/MidCamp/midcamp)
[![Code Climate](https://codeclimate.com/github/MidCamp/midcamp/badges/gpa.svg)](https://codeclimate.com/github/MidCamp/midcamp)

## Getting access

Ensure you have an Amazee account with your [SSH keys loaded](http://dashboard.amazeeio.cloud/settings).  If not reach out on the [MidCamp Slack](https://mid.camp/slack) in `#website-amazeeio`.

## DDEV-mode

### Prerequisites

- [DDEV](https://ddev.readthedocs.io/en/latest/users/install/)

### Getting started

Refer to [DDEV's documentation](https://ddev.readthedocs.io/en/latest/) for detailed information on configuration, customization and troubleshooting.

1. Start your local environment with `ddev start`
1. Run `ddev auth ssh` to load your ssh keys.
1. Import your local database and files via:
    1. `ddev pull lagoon`
    1. Skip files/db with `--skip-files` or `--skip-db`

## GitPod-mode

`.gitpod.yml` adds support for developing in a fully web-based environment called [GitPod](https://gitpod.io). GitPod doesn't have a great solution for forwarding ssh keys yet, so in order to be able to pull from Amazee you have two options:

1. [Add your existing SSH keys to GitPod (if you trust them)]https://github.com/gitpod-io/gitpod/issues/666#issuecomment-534124994)
1. [Generate a new key pod and give it to Amazee](https://docs.lagoon.sh/using-lagoon-advanced/ssh/) - you'll have to do this on every new instance, but Amazee will recognize new keys basically instantaneously, so it isn't too bad.

- [Rock and roll](https://gitpod.io/?autostart=true#https://github.com/MidCamp/midcamp)

## Lando-mode

### Prerequisites

- Lando (tested and working with version [`v3.0.23`](https://github.com/lando/lando/releases/tag/v3.0.23))

###  Getting started

Refer to [Lando's documentation](https://docs.lando.dev/) for detailed information on configuration, customization and troubleshooting.

1. Start your local environment with `lando start`
1. Import your local database and files via:
    1. `lando get-db`
    1. `lando get-files`

### Working with Lando

Some common Lando commands to be aware of:

- `lando start` starts your application
- `lando stop` stops your application
- `lando poweroff` stops Lando and any Lando apps currently running
- `lando restart` restarts your application
- `lando rebuild` rebuilds your application and re-runs any build steps
- `lando info` displays information about your local application, including relevant ports and connection info

All relevant tooling is included within the project containers, and as a result you can run all of this tooling from outside the containers by prefacing your commands with `lando `, for example:

- `lando composer require drupal/pathauto`
- `lando drush cache-rebuild`
- `lando npm install`

## Git workflow

When beginning work on a task, start a new branch:

`git checkout -b feature/my-great-work`

Amazee [workflows](https://lagoon.readthedocs.io/en/latest/using_lagoon/workflows/) will build a new environment for every branch that begins with `feature/` when it's pushed to GitHub. In order to access the environment for the branch above, visit https://nginx-midcamp-org-feature-my-great-work.us.amazee.io/. Databases for that environment are synced from Amazee dev, and it takes a few minutes after Circle completes for the install to complete.

### Deploying

To deploy a feature:

- merge your feature branch into `master`,
- (optional but plz) give it a few minutes to sync, then test your work on https://dev.midcamp.org/
- merge `master` to `production`.

See `deploy_tasks` in `.amazeeio.yml` for what happens after deploy (`updb`, `cim`, and more!)

## How do I Drupal?

### The Drupal root

This project uses [Composer Installers](https://github.com/composer/installers) and [Drupal Composer Scaffold](https://github.com/drupal/core-composer-scaffold) to assemble our Drupal root in `web`. Dig into `web` to find the both contrib Drupal code (installed by composer) and custom Drupal code (included in the git repository).

### Adding modules

* Download modules with composer: `lando composer require drupal/bad_judgement:^8.1`
* Enable the module: `lando drush en bad_judgement`
* Export the config with the module enabled: `lando drush config-export`
* Commit the changes to `composer.json`, `composer.lock`, and `conf/drupal/config/core.extension.yml`. The module code itself will be excluded by the project's `.gitignore`.

### Working on the theme

The hatter theme has been flattened (it was a separate repo with Patternlab) to go to the theme folder, for best results use the npm version specified in `.nvmrc` and then `npm install` to get the dependencies for the build and then the following commands compile assets:

* `npm run build` - Builds CSS and JS once
* `npm start` - Starts a watch process that will recompile CSS and JS whenever a change is made to the source.

If git conflicts occur in the:
* Compiled CSS or JS, run `npm run build` and add the result.
* Source SCSS or JS file, first address the conflict, then run `npm run build` and add the result.

### Patching modules

Sometimes we need to apply patches from the Drupal.org issue queues. These patches should be applied using composer using the [Composer Patches](https://github.com/cweagans/composer-patches) composer plugin.

## How do I run tests?

* `lando behat`
* `lando phpcs features/bootstrap web/modules/custom`
* `lando phpmd web/modules/custom text .phpmd.xml`
  * This should be configured to show the same errors triggered by Code Climate that you see on the Pull Request.

## Troubleshooting

Be sure to visit [Lando's troubleshooting page](https://docs.lando.dev/help/logs.html).

If your local development environment is behaving unexpectedly, you may need to do restart, reinstall, or rebuild your application.  Try the commands below in order, the fastest/lightest options are at the top.

| Command           | Description                                     |
|-------------------|-------------------------------------------------|
| `lando restart`   | Restarts the app, preserving container state    |
| `lando install`   | Clean Drupal install                            |
| `lando rebuild`   | Restarts the app, preserving data but not state |
| Restart Docker    | Can sometimes help build steps that time out    |
| `lando destroy`   | Destroys the app, preserves data                |
| `rm -rf ~/.lando` | Clears your Lando cache                         |

## Amazee.io Drupal Docker development environment

If you are still using the old tooling from Amazee, please refer to the
[legacy documentation](docs/amazee-docker-environment.md)
