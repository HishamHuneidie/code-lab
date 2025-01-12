#!/bin/bash
hostip_mac=$(nslookup docker.for.mac.localhost | awk '/^Address: / { print $2 ; exit }')
hostip_other=$(ip route show | awk '/default/ {print $3}')

if [ -z "$hostip_mac" ]; then
  hostip=$hostip_other
else
  hostip=$hostip_mac
fi

cat >/etc/php/8.2/mods-available/xdebug.ini <<EOL
[xdebug]
zend_extension=xdebug.so
xdebug.client_host=${hostip}
xdebug.client_port=9003
xdebug.idekey=PHPSTORM
xdebug.mode=debug
xdebug.log_level = 0
xdebug.remote_host=host.docker.internal
xdebug.remote_port=9003
EOL
