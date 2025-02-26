To apply the recipe run.

```
ddev drush site-install --existing-config
ddev ssh
cd web
php core/scripts/drupal recipe recipes/custom/midcamp -vvv
ddev drush cr ddev drush --uid=4 uli
```
