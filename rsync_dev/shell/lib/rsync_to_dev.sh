#!/bin/bash
config_file=$1
OLD_IFS="$IFS"
path=`pwd`
IFS="
"
for line in $(cat ../config/$config_file)
do
    IFS=" "
    arr=($line)
    first=${line:0:1}
    if [ $first == "#" ];then
        continue
    fi
    project=${arr[1]}
    IFS="
    "
    group=""
    if [ $project == "book.appserver.wnzhuishu.com" ];then
        group="server"
    elif [ $project == "payment.appserver.wnzhuishu.com" ];then
        group="server"
    elif [ $project == "user.appserver.wnzhuishu.com" ];then
        group="server"
    elif [ $project == "appapi.wnzhuishu.com" ];then
        group="user_site"
    elif [ $project == "gzh.bfkanshu.com" ];then
        group="user_site"
    elif [ $project == "gzh.mxzhuishu.com" ];then
        group="user_site"
    elif [ $project == "gzh.nhbook.com" ];then
        group="user_site"
    elif [ $project == "gzh.xyzhuishu.com" ];then
        group="user_site"
    elif [ $project == "jump.bfkanshu.com" ];then
        group="user_site"
    elif [ $project == "jump.mxzhuishu.com" ];then
        group="user_site"
    elif [ $project == "jump.nhbook.com" ];then
        group="user_site"
    elif [ $project == "jump.xyzhuishu.com" ];then
        group="user_site"
    elif [ $project == "m.bfkanshu.com" ];then
        group="user_site"
    elif [ $project == "m.meigui.com" ];then
        group="user_site"
    elif [ $project == "m.mxzhuishu.com" ];then
        group="user_site"
    elif [ $project == "m.nhbook.com" ];then
        group="user_site"
    elif [ $project == "m.xyzhuishu.com" ];then
        group="user_site"
    elif [ $project == "www.bfkanshu.com" ];then
        group="user_site"
    elif [ $project == "www.meigui.com" ];then
        group="user_site"
    elif [ $project == "www.mxzhuishu.com" ];then
        group="user_site"
    elif [ $project == "www.nhbook.com" ];then
        group="user_site"
    elif [ $project == "www.xyzhuishu.com" ];then
        group="user_site"
    elif [ $project == "www.meigui.com" ];then
        group="user_site"
    elif [ $project == "www.public_login.com" ];then
        group="user_site"
    elif [ $project == "www.wnzhuishu.com" ];then
        group="user_site"
    elif [ $project == "zs.gzh.com" ];then
        group="user_site"
    elif [ $project == "gzh.gzh.com" ];then
        group="user_site"
    elif [ $project == "www.wnliebian.com" ];then
        group="user_site"
    elif [ $project == "m.wnliebian.com" ];then
        group="user_site"     
    fi
    if [ "$group" == "" ];then
        echo "未知项目，所属组未找到"
        exit
    fi
    IFS=" "
   if [ ${arr[2]} = "master" ];then
       echo "不允许同步公共目录master"
       exit
   fi
    if [  -d "${arr[0]}" ];then
            rsync  -r -t -v --exclude-from=$path/lib/exclude_list.conf ${arr[0]}/ www@106.15.108.76:/data_judian/$group/${arr[1]}/${arr[2]} --delete
    fi
    IFS=OLD_IFS
done