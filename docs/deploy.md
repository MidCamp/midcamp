# Deploying to Pantheon

This document describes the process of deploying changes to Pantheon. 

## Steps

1. Cut a tag on master
1. Run the build
1. Review changes
1. Generate a database artifact and install on Acquia
1. Sync files
1. Deploy the code

**If there are configuration changes**, you MUST download, review, and incorporate the current production configuration.

## Cut a tag

For your latest code changes, cut a tag: https://github.com/palantirnet/harris/releases

## Run the build

1. Make sure SSH key forwarding is set up on your VM
1. Pull the tags from GitHub
1. Make sure that you are on your new tag, and your working tree is clean
1. Run the deploy script

### Pull tags
Run `git pull`. If there are new tags, this will grab them.

Run `git status`; it should say `nothing to commit, working directory clean`. If it does not, then your build may contain things that are not in the latest tag. You may still run the build, but your artifact will not receive the build tag.

## Deploy the code

### SSH key forwarding
In order to deploy, you need SSH access to the site on Acquia. To set this up, add your public key to your Acquia account; this will grant you SSH access to *all* of your Acquia projects.

You also need to make sure that SSH forwarding is set up for your Vagrant environment, since the `deploy` phing script needs to push code to Acquia using your SSH credentials.

To set up forwarding:

1. Create a `config.ssh` file at the project root inside the Vagrant environment with the following line:

  ```
  config.ssh.forward_agent = true
  ```
1. Exit out of your current Vagrant SSH session and start a new one (`vagrant ssh`)

### Run the deploy script
From your VM:

```
vendor/bin/phing deploy -Dpush=n
```

### Review changes
Verify that the changes included in this build are expected. Try running `git diff HEAD^ --stat` to show affected files.

### Push the build
You may manually push the build with `cd artifacts/acquia; git push`

You may also skip the review and push steps by running the deployment using the `push=y` property:

```
vendor/bin/phing deploy -Dpush=y
```
