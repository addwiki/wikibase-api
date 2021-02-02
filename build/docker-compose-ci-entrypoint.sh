#!/bin/bash

set -x

# Grab Wikibase
# TODO some test container should just be created with this in...
curl -LO https://extdist.wmflabs.org/dist/extensions/Wikibase-REL1_35-ea86f45.tar.gz
tar -xzf Wikibase-REL1_35-ea86f45.tar.gz -C extensions

# Wait for the DB to be ready?
/dc-scripts/wait-for-it.sh $MYSQL_SERVER:3306 -t 300
sleep 1
/dc-scripts/wait-for-it.sh $MYSQL_SERVER:3306 -t 300

# Install MediaWiki
php maintenance/install.php --server="http://localhost:8877" --scriptpath= --dbtype mysql --dbuser $MYSQL_USER --dbpass $MYSQL_PASSWORD --dbserver $MYSQL_SERVER --lang en --dbname $MYSQL_DATABASE --pass LongCIPass123 SiteName CIUser

# Settings to make testing easier
echo "require_once \"\$IP/extensions/Wikibase/vendor/autoload.php\";" >> LocalSettings.php
echo "require_once \"\$IP/extensions/Wikibase/repo/Wikibase.php\";" >> LocalSettings.php
echo "require_once \"\$IP/extensions/Wikibase/repo/ExampleSettings.php\";" >> LocalSettings.php

# Update MediaWiki & Extensions
php maintenance/update.php --quick

# Run apache
apache2-foreground