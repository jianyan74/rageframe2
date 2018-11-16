#!/bin/bash

# 备份文件存储目录
storeDir='/www/wwwroot/rageframe/common/backup'
# mysql 启动目录
runDir='/www/server/mysql/bin'
# mysql 账号
username='root'
# mysql 密码
password=''
# mysql 主机地址
server='127.0.0.1'
# 单个数据库 如果要多个例如:rageframe1 rageframe2
database='rageframe'

# --------------开始备份-------------- #

echo "start dump mysql..."

sqlName=`date +%Y%m%d-%H%M%S` # 备份sql名称
mkdir -p $storeDir
$runDir/mysqldump -h $server -u$username -p$password $database> $storeDir/$sqlName.sql

echo "dump ok"

# --------------开始压缩-------------- #

echo "start tar..."

# 压缩后的文件名
tarFileName=$sqlName.sql.tar.gz
cd $storeDir/
tar zcf $tarFileName $sqlName.sql

# -f是判断压缩文件是否存在
if [ -f $tarFileName ]
then
rm -rf $sqlName.sql
fi

# 删除超过7天的备份数据，保留3个月里的 10号 20号 30号的备份数据
find $storeDir/ -mtime +7 -name '*[1-9]-*.sql.tar.gz' -exec rm -rf {} \;
find $storeDir/ -mtime +92 -name '*.sql.tar.gz' -exec rm -rf {} \;

echo "tar ok"

######################
#
# 创建定时任务
# crontab –e
# 0 2 * * * /to-your-project-path/common/shell/mysql-backup.sh
#
######################