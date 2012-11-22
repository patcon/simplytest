#!/bin/sh

# Start X Virtual Framebuffer
DISPLAY=:99.0 sh -e /etc/init.d/xvfb start

# Create MySQL Database
mysql -e "CREATE DATABASE $PROJECT_NAME;"

# Install Drush
pear channel-discover pear.drush.org
pear install drush/drush-$DRUSH_VERSION
phpenv rehash

# Download Selenium server
wget -O $WORKSPACE/selenium-server.jar http://selenium.googlecode.com/files/selenium-server-standalone-$SELENIUM_VERSION.jar

# Download CasperJs
cd $WORKSPACE
git clone git://github.com/n1k0/casperjs.git
cd casperjs
git checkout tags/$CASPERJS_VERSION
