FROM monogramm/docker-mautic-base:2.16-apache

# Overwrite config init script
COPY entrypoint.sh makeconfig.php /
COPY mautic.crontab /etc/cron.d/mautic

# Add requirements for common extensions
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

ARG VERSION=unknown
ARG VCS_REF=unknown
ARG BUILD_DATE=unknown

LABEL maintainer="Monogramm Maintainers <opensource at monogramm dot io>" \
    version=$VERSION \
    org.label-schema.vcs-ref=$VCS_REF \
    org.label-schema.vcs-url="https://github.com/Monogramm/docker-mautic" \
    org.label-schema.build-date=$BUILD_DATE \
    org.label-schema.name="Mautic" \
    org.label-schema.description="Open Source Marketing Automation solution" \
    org.label-schema.url="https://www.mautic.org/" \
    org.label-schema.vendor="Acquia" \
    org.label-schema.version=$VERSION \
    org.label-schema.schema-version="1.0"
