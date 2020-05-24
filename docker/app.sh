#!/usr/bin/env sh

set -o errexit
set -o nounset

# Set the laravel version
if ! [ ${APP_VERSION:-} ]
then
  if ! [ ${MAX_VERSION:-} ]
  then
    ups "You have not specified a version of laravel"
  else
    APP_VERSION="${MAX_VERSION}"
  fi
fi

# Uncompatible laravel version supplied
if
  [ "${APP_VERSION%%.*}" -lt "${MIN_VERSION%%.*}" ] ||
  [ "${APP_VERSION%%.*}" -gt "${MAX_VERSION%%.*}" ]
then
  ups "App version must be between ${MIN_VERSION} and ${MAX_VERSION}"
fi

APP_FILES="${APPS_FILES:-/var/www/apps}/${APP_VERSION}"

# Verify that the project exists and is an application
# of laravel ready to go.
if test -d "${APP_FILES}" && composer --working-dir="${APP_FILES}" dump; then
  echo "There is a working Laravel project in ${APP_FILES}, omitting..."
else
  echo "I will create a new Laravel project in ${APP_FILES}, using ${APP_VERSION} version."
  rm -rf ${APP_FILES}
  composer create-project --prefer-dist --no-install --no-scripts -vvv laravel/laravel ${APP_FILES} "^${APP_VERSION}"

  # Save a copy of composer.json
  if ! test -f "${APP_FILES}/composer.json.backup"; then
    cp -v "${APP_FILES}/composer.json" "${APP_FILES}/composer.json.backup"
  fi

  # Merge the composer files
  echo "Merging ${APP_FILES}/composer.json con ${USER_FILES}/composer.json"
  php "${USER_FILES}/merge-json.php" \
      "${APP_FILES}/composer.json" "${USER_FILES}/composer.json" \
      "${APP_FILES}/composer.json"

  # Install dependencies
  cp "${APP_FILES}/.env.example" "${APP_FILES}/.env"
  composer -vvv --no-autoloader --no-scripts --working-dir="${APP_FILES}" install
fi

# Allow all permisions to storage and cache
chmod -vR 777 "${APP_FILES}/storage" "${APP_FILES}/bootstrap/cache"

# Link development files
rm -vrf "${APP_FILES}/database" "${APP_FILES}/app/Restql"
ln -vfs ${USER_FILES}/app/*.php "${APP_FILES}/app/"
ln -vfs ${USER_FILES}/routes/*.php "${APP_FILES}/routes/"
ln -vfs ${USER_FILES}/config/*.php "${APP_FILES}/config/"
ln -vfs "${USER_FILES}/app/Restql" "${APP_FILES}/app/Restql"
ln -vfs "${USER_FILES}/database" "${APP_FILES}/database"

# Dump for discover new files
composer --working-dir="${APP_FILES}" dump
php "${APP_FILES}/artisan" key:generate
php "${APP_FILES}/artisan" optimize:clear

# Link the public folder for nginx compatibility.
if ! test -d "/usr/share/nginx"; then
  mkdir -vp /usr/share/nginx
else
  rm -vrf /usr/share/nginx/html
fi

ln -vfs "${APP_FILES}/public" /usr/share/nginx/html
# Create current directory for externals commands
rm -rvf "${APPS_FILES}/current"
ln -vfs ${APP_FILES} "${APPS_FILES}/current"

# Start the FPM Service
echo "Starting..."
exec php-fpm
