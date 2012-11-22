#!/bin/sh

STATUS=0

# Run CasperJS
cd $WORKSPACE
DISPLAY=:99.0 ./casperjs/bin/casperjs test $WORKSPACE/build/profiles/$PROJECT_NAME/tmp/tests/casperjs/
[ $? -eq 0 ] || STATUS=1

sleep 10

# Run selenium server for Behat
DISPLAY=:99.0 java -jar $WORKSPACE/selenium-server.jar -p 4444 &
sleep 10

# Run behat tests
cd $WORKSPACE/build/profiles/$PROJECT_NAME/tmp/tests/behat
touch behat.local.yml
./bin/behat
[ $? -eq 0 ] || STATUS=1

# Exit with error if any of the suites fail (non-zero exits on suites set STATUS=1)
exit $STATUS
