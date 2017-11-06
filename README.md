Imperius project
========

A Symfony project created on November 5, 2017, 2:47 pm.

## Set up

### MacOS
 1. https://github.com/EugenMayer/docker-sync/wiki/1.-Installation (done once system wide)
 2. clone repo
 3. composer install + /etc/hosts imperius.home
 4. Run `bin/docker-build` script
 5. Run `docker-sync-stack start`
 6. Run `bin/composer update`
 7. Run `docker/tools/cache_clear`
 8. http://imperius.home:8081/app_dev.php

### Containers started on demand
 1. php-cli: `docker run --rm --volumes-from code cli c:c -e dev`
 
### Helper scripts located in `docker/tools`
 1. `cache_clear`
  1. Runs `cli` container witch cache:clear command on dev environment
  2. Sets `777` mode on cache and logs using `exec` on `code` container
