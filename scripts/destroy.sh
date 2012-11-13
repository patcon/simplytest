#!/bin/bash

# @todo Refactor ShellScripts #1836036.

# Make sure we run as root.
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

# Make sure all arguments are given.
if [[ "$#" -ne 1 ]]; then 
  echo "$0 [id]" 1>&2
  exit 1
fi

# Absolute path to script dir.
DIRECTORY="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Script parameters.
S_ID=$1

# Load function library.
source "$DIRECTORY/sources/common"

# Destruct environment.
s_destruct $S_ID

# Tell the server that a slot has become free.
s_ste 6
