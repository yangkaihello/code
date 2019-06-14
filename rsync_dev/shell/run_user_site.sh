#!/bin/bash
cd `dirname $0`
path=`pwd`
echo "当前目录:$path"
# 调试模式
while true; do sh ./lib/rsync_to_dev.sh sync_user_site_mapping.conf; sleep 2; done
# 后台运行模式
#while true; do ./rsync_to_dev.sh sync_user_site_mapping.conf user_site > /dev/null 2>&1; sleep 2; done &