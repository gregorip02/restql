#!/usr/bin/env sh

set -o errexit
set -o nounset

# Set the laravel version
if ! [ ${APP_VERSION:-} ]
then
  if ! [ ${MAX_VERSION:-} ]
  then
    ups "No ha especificado una versión de laravel"
  else
    APP_VERSION="${MAX_VERSION}"
  fi
fi

# Uncompatible laravel version supplied
if
  [ "${APP_VERSION%%.*}" -lt "${MIN_VERSION%%.*}" ] ||
  [ "${APP_VERSION%%.*}" -gt "${MAX_VERSION%%.*}" ]
then
  ups "La versión de laravel debe estar entre ${MIN_VERSION} & ${MAX_VERSION}"
fi

APP_FILES="${APPS_FILES:-/var/www/apps}/${APP_VERSION}"

# Verify that the project exists and is an application
# of laravel ready to go.
if test -d "${APP_FILES}" && composer --working-dir="${APP_FILES}" dump; then
  echo "Hay un proyecto de Laravel ${APP_VERSION} creado, omitiendo..."
  php "${APP_FILES}/artisan" optimize:clear
else
  echo "Creando nuevo proyecto de Laravel ${APP_VERSION} en ${APP_FILES}"
  rm -rf ${APP_FILES}
  composer create-project --prefer-dist --no-install --no-scripts -vvv laravel/laravel ${APP_FILES} "^${APP_VERSION}"

  # Save a copy of composer.json
  if ! test -f "${APP_FILES}/composer.json.backup"; then
    cp -v "${APP_FILES}/composer.json" "${APP_FILES}/composer.json.backup"
  fi

  # Merge the composer files
  echo "Combinando ${APP_FILES}/composer.json con ${USER_FILES}/composer.json"
  php "${USER_FILES}/merge-json.php" \
      "${APP_FILES}/composer.json" "${USER_FILES}/composer.json" \
      "${APP_FILES}/composer.json"

  # Install dependencies
  composer -vvv --no-autoloader --no-scripts --working-dir="${APP_FILES}" install
  cp "${APP_FILES}/.env.example" "${APP_FILES}/.env"
fi

# Allow all permisions to storage and cache
chmod -vR 777 "${APP_FILES}/storage" "${APP_FILES}/bootstrap/cache"

echo "Creando enlaces simbolicos de los archivos de desarrollo"
rm -vrf "${APP_FILES}/database"
ln -vfs ${USER_FILES}/app/*.php "${APP_FILES}/app/"
ln -vfs ${USER_FILES}/routes/*.php "${APP_FILES}/routes/"
ln -vfs ${USER_FILES}/config/*.php "${APP_FILES}/config/"
ln -vfs "${USER_FILES}/database" "${APP_FILES}/database"

# Dump for discover new files
composer --working-dir="${APP_FILES}" dump
php "${APP_FILES}/artisan" key:generate

# Link the public folder for nginx compatibility.
if ! test -d "/usr/share/nginx"; then
  mkdir -vp /usr/share/nginx
else
  rm -vrf /usr/share/nginx/html
fi

ln -vfs "${APP_FILES}/public" /usr/share/nginx/html
# Create current directory for externals commands
ln -vfs ${APP_FILES} "${APPS_FILES}/current"

# Start the FPM Service
echo "Starting..."
exec php-fpm
