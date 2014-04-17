cd /home/www/

svn export --force https://subversion.assembla.com/svn/adserum/branches/devel adserum/

cd /home/www/adserum/

chown -R www-data:www-data modules
chmod -R 755  modules


chown -R www-data:www-data sites/api.adserum.com/application
chmod -R 755  sites/api.adserum.com/application

chown -R www-data:www-data sites/panel.adserum.com/application
chmod -R 755  sites/panel.adserum.com/application

rm -rf sites/api.adserum.com/application/cache/*
rm -rf sites/panel.adserum.com/application/cache/*

#rm -rf sites/api.adserum.com/application/logs/*
#rm -rf sites/panel.adserum.com/application/logs/*


service apache2 reload


chmod 777 sys/backup.sh