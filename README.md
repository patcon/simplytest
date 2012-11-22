simplytest.me
=============

[![Build
Status](https://secure.travis-ci.org/patcon/simplytest.png?branch=travis-ci)](https://travis-ci.org/patcon/simplytest)

An online service that provides on-demand sandbox environments for
evaluating drupal.org project like modules, themes and distributions.

Simple, fast and for free!


Modules
=======

## simplytest_import
  Config: /admin/simplytest/import
  Let's you import project data from XML.

## simplytest_projects
  Provides an API to import and fetch project data from drupal.org as well
  as available versions (branches and tags).
  Drupal.org does currently not offer an official API to access such data,
  therefore the project pages are parsed for getting data of unknown projects
  (projects that were not imported initially by XML). Also the list of current
  heads and tags is parsed from the drupal.org repository viewer.
  Config: /admin/simplytest/projects

## simplytest_launch
  Provides the "simplytest launcher" block.
  The probably most important block with an autocomplete textfield an a project
  version select bock.
  Also handles flood protection, configure: /admin/simplytest/flood

## simplytest_submissions
  Manages the submissions made through the launcher.
  Config: /admin/simplytest/submission
  Submission monitor: /admin/simplytest/monitor

## simplytest_progress
  Provides a progress bar, showing the current state of a submission.
  (Based on Batch API, tough it wasn't good for observing states).

## simplytest_sponsors
  Provides two "sponsor" blocks.
  - Block "simplytest sponsors - sponsor list":
    Shows a list of all sponsors by small logos below the submission block.
  - Block "simplytest sponsors - advertisement":
    Shows a random advertisement of one of the sponsors.
  The list of sponsors, their order, logo and advertisement is configurable at:
    /admin/simplytest/sponsors

## simplytest_issues
  Provides the "simplytest issues" block that fetches the current state of
  the issue queue from drupal.org/project/simplytest and caches it.

## simplytest_servers
  Manages the available servers, their selections and the execution of commands
  (mostly the spawn.sh script for building a sandbox environment).
  Config: /admin/simplytest/servers

Setup
=====

After the usual drupal installation you should begin with

  1. Importing initial project data.

  The submission autocomplete textfield for choosing a project should
  have some initial data to work with.
  Download the current project list with
  $ wget http://updates.drupal.org/release-history/project-list/all
  Go to /admin/simplytest/import, enter the path to the downloaded XML and
  hit 'Start'. Importing the initial project data will take several minutes.
  NOTE: It's faster to import the list from an existing database dump.

  2. Setup the/a worker server.

  To actually provide any functionality, submissions must be executed on a
  external worker server with the build scrips set up and executable.
  Follow the documentation in ./scripts/README.txt for further information.

  3. Referencing the servers on the site.

  Configure the simplytest.me site to make use of the server, by adding a new
  server on /admin/simplytest/servers.
  NOTE: New servers will only be added if [X] Active is ticked!
  Most fields should be self explainable:
  Name:             Only for referencing a human readable name on the
                    simplytest.me site.
  Server Hostname:  The hostname of the server to connect to by ssh2, also used
                    as main hostname for sandbox sites.
                    "s1.simplytest.me" -> "[id].s1.simplytest.me"
  Slots:            The current count of slots available on the server.
                    One slot stands for one sandbox environment.
  Spawn script:     The absolute path to the script for building the site.
                    Eg.: /home/spawner/spawn.sh

This was the basic configuration to make the service itself work.
You should now be able to submit a project and launch a sandbox environment.
