#!/usr/bin/env sh

set -o errexit
set -o nounset

# Error handler
ups()
{
  echo "Algo ha salido mal: $1" >&2
  exit 1
}

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

create_laravel_app()
{
  echo "Creando nuevo proyecto de Laravel ${APP_VERSION} en ${APP_FILES}"
  # Verificar que el proyecto exista y sea una aplicación
  # de laravel lista para funcionar.
  if test -d "${APP_FILES}" && php "${APP_FILES}/artisan" up; then
    echo "Hay un proyecto de Laravel ${APP_VERSION} creado, omitiendo..."
  else
    rm -vrf ${APP_FILES}
    composer create-project -vvv laravel/laravel ${APP_FILES} "^${APP_VERSION}"
  fi

  # Generate new laravel app-key
  php "${APP_FILES}/artisan" optimize:clear

  # Allow all permisions to storage and cache
  chmod -vR 777 "${APP_FILES}/storage" "${APP_FILES}/bootstrap/cache"
}

link_user_files()
{
  echo "Creando enlaces simbolicos de los archivos de desarrollo"
  rm -vrf "${APP_FILES}/database"
  ln -vfs ${USER_FILES}/app/*.php "${APP_FILES}/app/"
  ln -vfs ${USER_FILES}/routes/*.php "${APP_FILES}/routes/"
  ln -vfs ${USER_FILES}/config/*.php "${APP_FILES}/config/"
  ln -vfs "${USER_FILES}/database" "${APP_FILES}/database"
}

merge_composer_files()
{
  echo "Combinando ${APP_FILES}/composer.json con ${USER_FILES}/composer.json"

  # Save a copy of composer.json
  if ! test -f "${APP_FILES}/composer.json.backup"; then
    cp -v "${APP_FILES}/composer.json" "${APP_FILES}/composer.json.backup"
  fi

  # Merge the files
  php "${USER_FILES}/merge-json.php" \
    "${APP_FILES}/composer.json" "${USER_FILES}/composer.json" \
    "${APP_FILES}/composer.json"

  if ! test -d "${APP_FILES}/vendor/gregorip02/restql"; then
    echo "Instalando paquete de desarrollo: gregorip02/restql"
    composer -vvv --working-dir="${APP_FILES}" require gregorip02/restql @dev
  fi
}

if create_laravel_app
then
  if link_user_files
    then
    merge_composer_files

    # Link the public folder for nginx compatibility.
    if ! test -d "/usr/share/nginx"; then
      mkdir -vp /usr/share/nginx
    else
      rm -vrf /usr/share/nginx/html
    fi
    ln -vfs "${APP_FILES}" /usr/share/nginx/html

    # Start the FPM Service
    echo "Starting..."
    exec php-fpm
  else
    ups "Error creado los enlaces simbolicos"
  fi
else
  ups "Error creando la aplicación de Laravel"
fi
