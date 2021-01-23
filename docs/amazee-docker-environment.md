###  Development Environment (Amazee)

This site uses the Amazee.io Drupal Docker development environment. If you already have this, you can skip this bit entirely. Their [documentation for setup](https://docs.amazee.io/local_docker_development/local_docker_development.html) is amazeballz, but if you just want to quickly get going on your Mac:

- [Install pygmy](https://pygmy.readthedocs.io/en/master/installation/l) with `gem install pygmy`
- Start pygmy with `pygmy up`

You will be prompted to provide the passphrase for your ssh key. Comply.


### Getting Things Started

From inside the project root, run:

- `composer install`
- `npm ci`
- `docker-compose up -d --build`

Visit [midcamp.org.docker.amazee.io](http://midcamp.org.docker.amazee.io) in your browser of choice.  At this point you should see a Drupal installation page.  See below for Drush commands to install the site.

### Stopping

Occasionally, you might want to stop working on this wonderful project. In that case:

- `pygmy stop       # Stop all pygmy services` if you just want a short pause, OR
- `pygmy down       # Stop and destroy all pygmy services` if you want to tear it all down, which is good especially if you're working with other local environments on the same machine.
- [just walk away](https://media.giphy.com/media/l0HlSlZmYf7lN2DEk/giphy.gif)—go outside, spend some time with family & friends, have a beverage, or just breathe.

### How do I work on this?

From inside the project root, type `docker-compose exec cli bash`

This is your project directory; run `drush` commands from here. Avoid using git from here, but if you must, make sure you configure your name and email for proper attribution:

```
git config --global user.email 'me@example.com'
git config --global user.name 'My Name'
```

### Using drush

You can run `drush` commands from anywhere within the repository, as long as you are ssh'ed into the container.

### Syncing your local environment

To get up and running locally, the easiest thing to do is update from the production database with:

```bash
dsql @production
```

If you want the files too, use:

```bash
drush rsync @production:%files @self:%files
```

## Troubleshooting

Be sure to visit the [Troubleshooting page](https://docs.amazee.io/local_docker_development/troubleshooting.html)

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

### I get the 'Could not load API JWT Token, error was:                                  [warning]'lagoon@ssh.lagoon.amazeeio.cloud: Permission denied (publickey). Error: no alias record could be found for source @production'

You will need someone on the Amazee Rocket Chat (https://amazeeio.rocket.chat/group/midcamp) to add your public SSH key on your account.


### I can't see new things in the style guide

Are you sure your changes got merged to [Hatter's](https://github.com/MidCamp/hatter-v2) `master`?

### Drush is super slow.

Your firewall may be blocking requests to Amazee. Running drush with LAGOON_DISABLE_ALIASES=true
may temporarily resolve the issue.

* `LAGOON_DISABLE_ALIASES=true drush <SOME-COMMAND-HERE>`
