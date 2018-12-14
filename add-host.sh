#!/bin/sh

# Define vars
HOSTS_PATH="/etc/hosts"
HOMESTEAD_DIR=~/Homestead

# Function for parsing YMAL files
parse_yaml() {
   local prefix=$2
   local s='[[:space:]]*' w='[a-zA-Z0-9_]*' fs=$(echo @|tr @ '\034')
   sed -ne "s|^\($s\)\($w\)$s:$s\"\(.*\)\"$s\$|\1$fs\2$fs\3|p" \
        -e "s|^\($s\)\($w\)$s:$s\(.*\)$s\$|\1$fs\2$fs\3|p"  $1 |
   awk -F$fs '{
      indent = length($1)/2;
      vname[indent] = $2;
      for (i in vname) {if (i > indent) {delete vname[i]}}
      if (length($3) > 0) {
         vn=""; for (i=0; i<indent; i++) {vn=(vn)(vname[i])("_")}
         printf("%s%s%s=\"%s\"\n", "'$prefix'",vn, $2, $3);
      }
   }'
}

# Push yaml vars into config variable
eval $(parse_yaml Homestead.yaml "config_")

#Addd project URL to hosts file
read -p "Add $config_ip as $config_hostname.test to $HOSTS_PATH [y/n]? (any other key to exit) " choice
case "$choice" in
  y|Y ) echo "(Root password required) "; echo "$config_ip $config_hostname.test" | sudo tee -a "$HOSTS_PATH";;
  * ) echo "Check the following before re-running script:";
      echo "- Value $HOSTS_PATH is correct";
      echo "- Value $config_ip is consistent with value in $HOMESTEAD_DIR/Homestead.yaml";
      echo "- Value $config_hostname.test is consistent with value in $HOMESTEAD_DIR/Homestead.yaml";
      exit 1;;

esac