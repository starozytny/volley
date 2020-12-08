#!/bin/sh

# configuration de l'utilisateur MySQL et de son mot de passe
DB_USER=$1
DB_PASS=$2
# configuration de la machine hébergeant le serveur MySQL
DB_HOST=$3
DB_NAME=$4

# sous-chemin de destination
OUTDIR=`date +%y_%m_%d`
# création de l'arborescence
mkdir -p backup/$OUTDIR
# boucle sur les bases pour les dumper
MYSQL_PWD=$DB_PASS mysqldump -u $DB_USER --single-transaction --skip-lock-tables $DB_NAME -h $DB_HOST > backup/$OUTDIR/$DB_NAME.sql