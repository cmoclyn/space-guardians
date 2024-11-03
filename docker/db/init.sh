#!/bin/bash

DB_NAME=${MYSQL_DATABASE_TEST}
DB_USER=${MYSQL_USER}

# Attendre que MySQL soit prêt
until mysql -h "localhost" -u "${MYSQL_USER}" -p"${MYSQL_PASSWORD}" -e "status"; do
  echo "Waiting for database connection..."
  sleep 5
done

# Créer la base de données de test et attribuer les droits
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<EOF
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`;
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'%';
FLUSH PRIVILEGES;
EOF