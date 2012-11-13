## FILES AND DIRECTORIES

- build.sh        Sets up a complete simplytest environment.
- spawn.sh        Spawns the build.sh script in the background.
- destroy.sh      Destroys an environment (triggered by at jobs).
- config          Scripts general configuration file.
- snippet.php     PHP info-snippet that is appended to all php requests.
- log/            Directory for all logfiles of build and destroy scripts.

## WARNING

As we let foreign people on a simplytest server with rights to execute PHP
code, make mysql queries and access the filesystem - though everything is as
restricted as possible - it can not be guaranteed that this server is secure
from any attacks. Make sure your server can be easily rolled back in trouble.
DO NOT USE THESE SCRIPTS ON A SERVER WHERE ANYTHING ELSE THAN THIS RUNS ON!

## DEPENDENCIES

Apache with fastCGI, suExec:
  apache2-mpm-worker
  apache2-suexec-custom
  libapache2-mod-fcgid

PHP5 CGI:
  php5-cgi
  php5-suhosin
  php5-gd

MySQL:
  mysql-client
  mysql-server

Other:
  drush (>= 5.7)
  at
  inotify-tools
  timeout
  dos2unix

## CONFIGURATION

See ./config file.

## INSTALLATION

After all dependencies are resolved no further installation are necessary.
Just upload them somewhere on the worker server.

The simplytest.me-site will connect by ssh2 to the server and needs to
execute the spawn.sh script as root user.
To do this without actually loggin in as root, create a new user
and add these lines to your sudoers file by $visudo:
  Cmnd_Alias SIMPLYTESTSPAWN_CMDS = [#1]
  [#2] ALL=(ALL) NOPASSWD: SIMPLYTESTSPAWN_CMDS
Replace:
  [#1]  Path to the spawn script.
  [#2]  Name of the new user (other than root).

## USAGE

Use build.sh to build a site with the script running in foreground
(eg. for testing) with the following parameters
$ ./build [id] [host] [git url] [type] [version (tags/heads)]
        [timeout (in minutes)] [project (shortname)]

[id]      The identification for this submission 1-16 (A-Za-Z0-9) characters.
          Will be used for the vhost, unix user and mysql user, database and
          password. Also logfiles will be saved as ./log/[id].log.
[host]    Hostname to use for this submission, eg.: s1.simplytest.me. The
          resulting vhost will be [id].s1.simplytest.me.
[git url] The projects git url to clone from, eg.:
          git://git.drupal.org/project/drupal.git.
[type]    The project type ('Drupal core', 'Module', 'Theme', 'Distribution').
[version] The branch or tag to checkout with git.
[timeout] Count of minutes until the destroying script is executed.
[project] The projects shortname as known from drupal.org.

Same parameters apply for the spawn script, though it executes the buildscript
as a seperate/background process.

A build environment will automatically be destroyed after [timeout] minutes
(using ´at´ jobs) but this can also be done manually by executing
$ ./destroy.sh [id]
as root user with the same id as entered for ./build.sh.

## OPTIMISATION

- Enable drush project caching and use git cloning.
  Edit/Create a drushrc.php configuration file and set:
  $options['cache'] = TRUE;
  $options['package-handler'] = 'git_drupalorg';

- Enable drush make to use git and caching for projects:
  Edit the make.download.inc file and comment out:
  'package-handler' => 'wget',

- Install APC cache:
  $ apt-get install php-apc

- Turn off usage of InnoDB (which is slower on installations):
  $ vim /etc/mysql/my.cnf
  
      skip-external-locking
      skip-innodb
      default_storage_engine=MyISAM

- MyIsam optimisation (Raise buffer and cache sizes):
  $ vim /etc/mysql/conf.d/myisam.cnf 

      [mysqld]
      bulk_insert_buffer_size=2G
      join_buffer_size=128M
      key_buffer_size=128M
      max_allowed_packet=32M
      query_cache_limit=64M
      read_buffer_size=10M
      read_rnd_buffer_size=2M
      sort_buffer_size=128M
      table_cache=1024
      tmp_table_size=128M

- Move MySQL database files into RAM

  See http://wolfgangziegler.net/node/14990
