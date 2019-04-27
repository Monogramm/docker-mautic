<?php
$stderr = fopen('php://stderr', 'w');

fwrite($stderr, "\nWriting initial Mautic config\n");

$parameters = array(
	'db_driver'      => 'pdo_mysql',
	'install_source' => 'Docker'
);

if (array_key_exists('MAUTIC_DB_HOST', $_ENV)) {
    // Figure out if we have a port in the database host string
    if (strpos($_ENV['MAUTIC_DB_HOST'], ':') !== false) {
        list($host, $port) = explode(':', $_ENV['MAUTIC_DB_HOST'], 2);
        $parameters['db_port'] = $port;
    }
    else {
        $host = $_ENV['MAUTIC_DB_HOST'];
    }
    $parameters['db_host'] = $host;
}
if (array_key_exists('MAUTIC_DB_NAME', $_ENV)) {
    $parameters['db_name'] = $_ENV['MAUTIC_DB_NAME'];
}
if (array_key_exists('MAUTIC_DB_TABLE_PREFIX', $_ENV)) {
    $parameters['db_table_prefix'] = $_ENV['MAUTIC_DB_TABLE_PREFIX'];
}
if (array_key_exists('MAUTIC_DB_USER', $_ENV)) {
    $parameters['db_user'] = $_ENV['MAUTIC_DB_USER'];
}
if (array_key_exists('MAUTIC_DB_PASSWORD', $_ENV)) {
    $parameters['db_password'] = $_ENV['MAUTIC_DB_PASSWORD'];
}
if (array_key_exists('MAUTIC_DB_BACKUP_TABLE', $_ENV)) {
    $parameters['db_backup_tables'] = (bool) $_ENV['MAUTIC_DB_BACKUP_TABLE'];
}
if (array_key_exists('MAUTIC_DB_BACKUP_PREFIX', $_ENV)) {
    $parameters['db_backup_prefix'] = $_ENV['MAUTIC_DB_BACKUP_PREFIX'];
}
if (array_key_exists('MAUTIC_TRUSTED_PROXIES', $_ENV)) {
    $proxies = explode(',', $_ENV['MAUTIC_TRUSTED_PROXIES']);
    $parameters['trusted_proxies'] = $proxies;
}

if (array_key_exists('MAUTIC_SECRET_KEY', $_ENV)) {
    $parameters['secret_key'] = $_ENV['MAUTIC_SECRET_KEY'];
}
if (array_key_exists('MAUTIC_SITE_URL', $_ENV)) {
    $parameters['site_url'] = $_ENV['MAUTIC_SITE_URL'];
}
if (array_key_exists('MAUTIC_LOCALE', $_ENV)) {
    $parameters['locale'] = $_ENV['MAUTIC_LOCALE'];
}

if (array_key_exists('MAUTIC_REMEMBERME_DOMAIN', $_ENV)) {
    $parameters['rememberme_domain'] = $_ENV['MAUTIC_REMEMBERME_DOMAIN'];
}

if (array_key_exists('MAUTIC_COOKIE_DOMAIN', $_ENV)) {
    $parameters['cookie_domain'] = $_ENV['MAUTIC_COOKIE_DOMAIN'];
}
if (array_key_exists('MAUTIC_COOKIE_SECURE', $_ENV)) {
    $parameters['cookie_secure'] = (bool) $_ENV['MAUTIC_COOKIE_SECURE'];
}
if (array_key_exists('MAUTIC_COOKIE_HTTP_ONLY', $_ENV)) {
    $parameters['cookie_httponly'] = (bool) $_ENV['MAUTIC_COOKIE_HTTP_ONLY'];
}

if (array_key_exists('MAUTIC_RSS_NOTIFICATION_URL', $_ENV)) {
    $parameters['rss_notification_url'] = $_ENV['MAUTIC_RSS_NOTIFICATION_URL'];
}

if (array_key_exists('PHP_INI_DATE_TIMEZONE', $_ENV)) {
    $parameters['default_timezone'] = $_ENV['PHP_INI_DATE_TIMEZONE'];
}

if (array_key_exists('MAUTIC_MAILER_TRANSPORT', $_ENV)) {
    $parameters['mailer_transport'] = $_ENV['MAUTIC_MAILER_TRANSPORT'];
}
if (array_key_exists('MAUTIC_MAILER_HOST', $_ENV)) {
    $parameters['mailer_host'] = $_ENV['MAUTIC_MAILER_HOST'];
}
if (array_key_exists('MAUTIC_MAILER_PORT', $_ENV)) {
    $parameters['mailer_port'] = (int) $_ENV['MAUTIC_MAILER_PORT'];
}
if (array_key_exists('MAUTIC_MAILER_USER', $_ENV)) {
    $parameters['mailer_user'] = $_ENV['MAUTIC_MAILER_USER'];
}
if (array_key_exists('MAUTIC_MAILER_PASSWORD', $_ENV)) {
    $parameters['mailer_password'] = $_ENV['MAUTIC_MAILER_PASSWORD'];
}
if (array_key_exists('MAUTIC_MAILER_ENCRYPTION', $_ENV)) {
    $parameters['mailer_encryption'] = $_ENV['MAUTIC_MAILER_ENCRYPTION'];
}
if (array_key_exists('MAUTIC_MAILER_AUTH_MODE', $_ENV)) {
    $parameters['mailer_auth_mode'] = $_ENV['MAUTIC_MAILER_AUTH_MODE'];
}


if (array_key_exists('MAUTIC_QUEUE_PROTOCOL', $_ENV)) {
    $parameters['queue_protocol'] = $_ENV['MAUTIC_QUEUE_PROTOCOL'];
}
if (array_key_exists('MAUTIC_RABBITMQ_HOST', $_ENV)) {
    $parameters['rabbitmq_host'] = $_ENV['MAUTIC_RABBITMQ_HOST'];
}
if (array_key_exists('MAUTIC_RABBITMQ_PORT', $_ENV)) {
    $parameters['rabbitmq_port'] = (int) $_ENV['MAUTIC_RABBITMQ_PORT'];
}
if (array_key_exists('MAUTIC_RABBITMQ_USER', $_ENV)) {
    $parameters['rabbitmq_user'] = $_ENV['MAUTIC_RABBITMQ_USER'];
}
if (array_key_exists('MAUTIC_RABBITMQ_PASSWORD', $_ENV)) {
    $parameters['rabbitmq_password'] = $_ENV['MAUTIC_RABBITMQ_PASSWORD'];
}


if (array_key_exists('MAUTIC_LDAP_HOST', $_ENV)) {
    $parameters['ldap_auth_host'] = $_ENV['MAUTIC_LDAP_HOST'];
}
if (array_key_exists('MAUTIC_LDAP_PORT', $_ENV)) {
    $parameters['ldap_auth_port'] = (int) $_ENV['MAUTIC_LDAP_PORT'];
}
if (array_key_exists('MAUTIC_LDAP_VERSION', $_ENV)) {
    $parameters['ldap_auth_version'] = (int) $_ENV['MAUTIC_LDAP_VERSION'];
}
if (array_key_exists('MAUTIC_LDAP_SSL', $_ENV)) {
    $parameters['ldap_auth_ssl'] = (bool) $_ENV['MAUTIC_LDAP_SSL'];
}
if (array_key_exists('MAUTIC_LDAP_STARTTLS', $_ENV)) {
    $parameters['ldap_auth_starttls'] = (bool) $_ENV['MAUTIC_LDAP_STARTTLS'];
}
if (array_key_exists('MAUTIC_LDAP_BASE_DN', $_ENV)) {
    $parameters['ldap_auth_base_dn'] = $_ENV['MAUTIC_LDAP_BASE_DN'];
}
if (array_key_exists('MAUTIC_LDAP_USER_QUERY', $_ENV)) {
    $parameters['ldap_auth_user_query'] = $_ENV['MAUTIC_LDAP_USER_QUERY'];
}
if (array_key_exists('MAUTIC_LDAP_USER_ATTR', $_ENV)) {
    $parameters['ldap_auth_username_attribute'] = $_ENV['MAUTIC_LDAP_USER_ATTR'];
}
if (array_key_exists('MAUTIC_LDAP_EMAIL_ATTR', $_ENV)) {
    $parameters['ldap_auth_email_attribute'] = $_ENV['MAUTIC_LDAP_EMAIL_ATTR'];
}
if (array_key_exists('MAUTIC_LDAP_FIRSTNAME_ATTR', $_ENV)) {
    $parameters['ldap_auth_firstname_attribute'] = $_ENV['MAUTIC_LDAP_FIRSTNAME_ATTR'];
}
if (array_key_exists('MAUTIC_LDAP_LASTNAME_ATTR', $_ENV)) {
    $parameters['ldap_auth_lastname_attribute'] = $_ENV['MAUTIC_LDAP_LASTNAME_ATTR'];
}
if (array_key_exists('MAUTIC_LDAP_FULLNAME_ATTR', $_ENV)) {
    $parameters['ldap_auth_fullname_attribute'] = $_ENV['MAUTIC_LDAP_FULLNAME_ATTR'];
}


$path     = '/var/www/html/app/config/local.php';
$rendered = "<?php\n\$parameters = ".var_export($parameters, true).";\n";

$status = file_put_contents($path, $rendered);

if ($status === false) {
	fwrite($stderr, "\nCould not write configuration file to $path, you can create this file with the following contents:\n\n$rendered\n");
}
