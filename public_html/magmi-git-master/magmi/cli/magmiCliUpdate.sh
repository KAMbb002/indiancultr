mail -s "Magmi Cli started" onlineops@exclusively.com < indexing.txt
php magmi.cli.php -profile=exclusively -mode=update
mail -s "Magmi Cli completed" onlineops@exclusively.com < magmiCliUpdate.log
