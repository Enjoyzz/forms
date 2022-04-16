FROM enjoys/php:8.0

RUN install-php-extensions bcmath intl gd xdebug

WORKDIR "/opt/project"

