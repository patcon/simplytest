#!/bin/bash

# Make sure all arguments are given.
if [[ "$#" -le 6 ]]; then 
  echo "$0 [id] [host] [git url] [type] [version (tags/heads)] [timeout (in minutes)] [project (shortname)]"
  exit 1
fi

# Deamonize (start this script again, but dedicated).
if [ "x$1" != "x--" ]; then
  $0 -- "$1" "$2" "$3" "$4" "$5" "$6" "$7" 1> /dev/null 2> /dev/null &
  exit 0
fi

# !!! Absolute path to script dir.
# Change this if this script is somewhere else than in the scripts dir.
DIRECTORY="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Get arguments.
S_ID=$2
S_HOST=$3
S_GITURL=$4
S_TYPE=$5
S_VERSION=$6
S_TIMEOUT=$7
S_PROJECT=$8

# !!! Start build script WITH ROOT PERMISSIONS.
# To do this without beeing root, add these lines to your sudoers file by visudo:
# Cmnd_Alias SIMPLYTESTSPAWN_CMDS = <PATH TO THIS SCRIPT>
# <THIS USERS NAME> ALL=(ALL) NOPASSWD: SIMPLYTESTSPAWN_CMDS
timeout 900 sudo "$DIRECTORY/build.sh" "$S_ID" "$S_HOST" "$S_GITURL" "$S_TYPE" "$S_VERSION" "$S_TIMEOUT" "$S_PROJECT" > "$DIRECTORY/log/$S_ID.log" 2>&1
