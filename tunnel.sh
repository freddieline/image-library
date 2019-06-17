#!/usr/bin/env bash

# Define path to tunnel script
TUNNEL=~/bin/tunnel

# Define the SSH settings
SSHPORT=2222
FROMPORT=3306
TOPORT=3306
USER=user
IP=192.168.10.10

# Create the tunnel
${TUNNEL} ${SSHPORT} ${FROMPORT} ${TOPORT} ${USER} ${IP}