FROM monogramm/docker-mautic-base:2.15.1-apache

LABEL maintainer="Mathieu BRUNOT <mathieu.brunot at monogramm dot io>"

# Overwrite config init script
COPY makeconfig.php /

# Add requirements for common extenions
RUN set -ex; \
    apt-get update; \
	apt-get install -y --no-install-recommends \
		libldap2-dev \
	; \
	\
	debMultiarch="$(dpkg-architecture --query DEB_BUILD_MULTIARCH)"; \
	docker-php-ext-configure ldap --with-libdir="lib/$debMultiarch"; \
	docker-php-ext-install \
		ldap \
	;
