#!/usr/bin/env bash

# Define path to tunnel script
TUNNEL=~/bin/tunnel

# Define the SSH settings
SSHPORT=22
FROMPORT=4731
TOPORT=4731
USER=user
IP=172.16.3.182

# Create the tunnel
${TUNNEL} ${SSHPORT} ${FROMPORT} ${TOPORT} ${USER} ${IP}