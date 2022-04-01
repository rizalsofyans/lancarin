#!/bin/bash
cd /var/www/html/lancarin
echo "enter your mysql root password"
mysqldump -u grameasy -pjnf6KkArxuStfpNP grameasy2 > db.sql
crontab -l > cron.txt
now=$(date)
git add .
git commit -m "update ${now}"
git push origin master
