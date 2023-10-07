
#!/bin/bash
DIRECTORY="/var/www/html/storage/files"
IMPORTER_PATH="/var/www/html/app/Services/Importer/"
IMPORTER_CLASS="Importer"
PARSER_CLASS="Parser"
if [ -d "$DIRECTORY" ]; then
  php -f "importer.php"
else
  echo "Error: Create directory - ${DIRECTORY}, fill xml files and start again"
  exit 1
fi