#!/bin/sh

# Install Classic Profile
cd $WORKSPACE
mv $PROJECT_NAME profile
drush make profile/build-$PROJECT_NAME.make build --yes
cd build
drush si $PROJECT_NAME \
  --sites-subdir=default \
  --db-url=mysql://root:@127.0.0.1/$PROJECT_NAME \
  --account-name=admin \
  --account-pass=admin \
  --site-mail=admin@example.com \
  --site-name=$PROJECT_NAME \
  --yes
drush cc all --yes

# Run composer
cd $WORKSPACE/build/profiles/$PROJECT_NAME/tmp/tests/behat
composer install

# Copy drush alias
cp $WORKSPACE/build/profiles/$PROJECT_NAME/tmp/tests/behat/$PROJECT_NAME.aliases.drushrc.php ~/.drush/

# Start drush webserver (sudo needed for port 80)
sudo drush @$PROJECT_NAME.local runserver --server=builtin 80 &
sleep 3 # give xvfb some time to rebuild

