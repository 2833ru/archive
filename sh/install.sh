#!bin/bash

# PHPShop Install
#sh install.sh localhost phpshop_pshp1 phpshop_pshp1 dGyEySHRO admin 123456 mail@phpshop.ru

RED='\033[01;31m'
GREEN='\033[01;32m'
YELLOW='\033[01;33m'
CYAN='\033[01;36m'
WHITE='\033[01;37m'
NC='\033[00m'
TMP='../backup/temp/'
V='1.0'

# help

case $1 in
     help|-h|/h)
           echo  "${CYAN}PHPShop Install ${V}${NC}"
		   echo  "${NC}Use the following command options: $0 host user_db dbase pass_db admin admin_pass admin_mail${NC}"
		   exit 0
          ;;
esac

if [ $# = 0 ] ; then 
    echo  "${CYAN}PHPShop Install ${V} ${NC}"
fi

# check path install
dump=../install/base_5.sql
if [ ! -s $dump ] ; then
   "Folder /install not found!"
   exit 0
   elif [ $# = 0 ] ; then 
   echo  "${NC}It is required to enter connection parametres to base MySQL"
fi

# MySQ authorization
if [ $1 ] ; then 
    host=$1
   else 
    echo -n "Host: "
   read host
fi

if [ $2 ] ; then 
    user_db=$2
   else 
   echo -n "User: "
   read user_db
fi

if [ $3 ] ; then 
    dbase=$3
   else 
   echo -n "Base: "
   read dbase
fi

if [ $4 ] ; then 
    pass_db=$4
   else 
   echo -n "Password: "
   read pass_db
fi


# write ini
echo " " | cat >> "../phpshop/inc/config.ini"
echo "# It is written by install.sh" | cat >> "../phpshop/inc/config.ini"
echo "[connect]" | cat >> "../phpshop/inc/config.ini"
echo "host=\"$host\";" | cat >> "../phpshop/inc/config.ini"
echo "user_db=\"$user_db\";" | cat >> "../phpshop/inc/config.ini"
echo "pass_db=\"$pass_db\";" | cat >> "../phpshop/inc/config.ini"
echo "dbase=\"$dbase\";" | cat >> "../phpshop/inc/config.ini"
echo "${GREEN}Connection parametres is finished..."

# check connect
result=`php cli.lib.php mysql`
case $result in
     done)
           echo  "${GREEN}Password for MySQL is correct...${NC}"
          ;;
     *)
          echo  -n "${NC}Mysql password failed validation, repeat? (y / n)${NC}"
		  read ready

		  if [ $ready = "y" ] ; then 
          sh $0 localhost
          exit 0
		  else exit 0
          fi
          ;;
esac

if [ $# = 0 ] ; then 
    echo  "${NC}It is required to specify personal data for admin"
fi

if [ $5 ] ; then 
   login=$5
   else 
   echo -n "Login: "
   read login
fi

if [ $6 ] ; then 
   pas=$6
   else 
   echo -n "Password: "
   read pas
fi

if [ $7 ] ; then 
   mail=$7
   else 
   echo -n "E-mail: "
   read mail
fi

mysql --host=$host --user=$user_db  --default-character-set=cp1251 --password=$pass_db  $dbase < $dump

# add admin
result=`php cli.lib.php user  $login $pas $mail`
if [ $result = "done" ] ; then 
   echo  "${GREEN}Create new admin is finished...${NC}"
   echo  "${CYAN}PHPShop installation is finished${NC}"
   rm -rf ../install

   #chmod
   chmod 775 ../license
   chmod 775 ../UserFiles/Image
   chmod 775 ../UserFiles/Files
   chmod 775 ../phpshop/admpanel/csv
   chmod 775 ../phpshop/admpanel/dumper/backup

else 
   echo  "${RED}Admin can not be created!${NC}"
fi