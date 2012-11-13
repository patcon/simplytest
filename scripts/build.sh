#!/bin/bash

# @todo Refactor ShellScripts #1836036.

# Make sure we run as root.
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root!"
   exit 1
fi

# Make sure all arguments are given.
if [[ "$#" -ne 7 ]]; then 
  echo "$0 [id] [host] [git url] [type] [version (tags/heads)] [timeout (in minutes)] [project (shortname)]"
  exit 1
fi

# Absolute path to script dir.
DIRECTORY="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Script parameters for global use.
S_ID=$1
S_HOST=$2
S_GITURL=$3
S_TYPE=$4
S_VERSION=$5
S_TIMEOUT=$6
S_PROJECT=$7

# Load function library.
source "$DIRECTORY/sources/common"

### PREPARE.

# Set state.
s_ste 1

lg "Prepare.."
s_prepare

### DOWNLOAD AND FETCH DEPENDENCIES.

# Set state.
s_ste 2

lg "Downloading project.."
s_project_download

lg "Fetch dependencies.."
s_project_fetch_dependencies

### INSTALL.
s_ste 3

lg "Installing project.."
s_project_install

### FINALIZE.
s_ste 4
lg "Finalizing.."

# Add infobar script snippet to index.php.
lg "Adding info snippet.."
s_addsnippet

# Make sure all files and directory have the correct group and user.
s_reset_environment_files "$S_ID"

# Set a timeout to destroy the environment.
lg "Set timeout to destroy job.."
s_settimeout "$DIRECTORY/destroy.sh $S_ID >>$DIRECTORY/log/$S_ID.log 2>&1" $S_TIMEOUT

### FINISHED
s_ste 5
