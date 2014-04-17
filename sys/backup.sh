#################
# FTP Backup Script
# Backup fro files and MySQL, uploads to FTP and deletes old files.
# Chema@garridodiaz.com 
# 2013-08-26
# GPLv3
#################


#################
####CONFIGS######
#################

#FTP details
FTP_SERVER=""  
FTP_LOGIN=""     
FTP_PASS="" 
#FTP expires backup days
NDAYS=14

#MYSQL details
DBH="localhost" 
DBU="" 
DBP="" 

#GET DATE
DATE=$(date  +"%Y-%m-%d")

#Where do we store the Backups - END POINT FOR FILES
EPF="/home/backups/"

#WEB Backup for files and redis
#FROM="/var/www/ /root/var/lib/redis/6379" 
#FROM="/home/www/ /root/dump.rdb" 
FROM="/home/www/ /home/redis/"
TO="$EPF$DATE-bkp.tgz" 


#################
######START######
#################

#################
#1 - Files Backup
#################
echo "Compress files from $FROM to $TO"
tar czf $TO $FROM

#################
#2 - MySQL Backup
#################
DBO="$EPF$DATE-dc" 
echo "MySQL backup $DBO"
mysqldump -h $DBH -u$DBU -p$DBP --all-databases  > $DBO.sql 
tar czf $DBO.sql.tar.gz $DBO.sql
rm $DBO.sql

#################
#3 - UNIQUE BACKUP
#################
FROMB="$DBO.sql.tar.gz $TO" 
TOB="$EPF$DATE-backup.tgz" 
echo "Compress final file and cleaning $TOB"
tar czf $TOB $FROMB
rm $DBO.sql.tar.gz $TO


#################
#4 - removes old files from FTP
#################
# work out our cutoff date
MM=`date --date="$NDAYS days ago" +%b`
DD=`date --date="$NDAYS days ago" +%d`

echo "Removing files from FTP older than $MM $DD"

# get directory listing from remote source
listing=`ftp -i -n $FTP_SERVER <<EOMYF 
user $FTP_LOGIN $FTP_PASS
binary
ls
quit
EOMYF
`
lista=( $listing )

# loop over our files
for ((FNO=0; FNO<${#lista[@]}; FNO+=9));do

  # check the date stamp
  if [ ${lista[`expr $FNO+5`]}=$MM ];
  then
    if [[ ${lista[`expr $FNO+6`]} -lt $DD ]];
    then
      # Remove this file
      echo "Removing ${lista[`expr $FNO+8`]}"
      ftp -i -n $FTP_SERVER <<EOMYF2 
      user $FTP_LOGIN $FTP_PASS
      binary
      delete ${lista[`expr $FNO+8`]}
      quit
EOMYF2
    fi
  fi
done

#################
#5- move Backup to FTP
#################
echo "Moving to FTP"
ftp -n $FTP_SERVER <<END
        user $FTP_LOGIN $FTP_PASS
        put $TOB /$DATE-backup.tgz
        quit
END