<?php
$httpUtils = new \SimpleSAML\Utils\HTTP();
$protocol = "https://";

if (getenv('NO_HTTPS') == 'true') {
    $protocol = "http://";
}

if (getenv('PORT') != '') {
    $port = getenv('PORT');
    $port = ':' . $port;
} else {
    $port = '';
}

$metadata_sources = [];
$metadata_sources[] = ['type' => 'flatfile'];
if (getenv('MDX_URL') != '') {
    $metadata_sources[] = ['type' => 'mdx', 'server' => getenv('MDX_URL')];
}
if (getenv('METADATA_URL') != '') {
    $metadata_sources[] = ['type' => 'xml', 'url' => getenv('METADATA_URL')];
}

$config = [
    'baseurlpath' => $protocol.getenv('VIRTUAL_HOST').$port.'/simplesaml/',

    'application' => [
        'baseURL' => '$protocol'.getenv('VIRTUAL_HOST').$port.'/',
    ],

    'cachedir' => '/tmp/cache/simplesamlphp',
    'certdir' => 'cert/',
    'technicalcontact_name' => 'Administrator',
    'technicalcontact_email' => 'na@example.org',
    'timezone' => null,
    'secretsalt' => ((getenv('SIMPLESAMLPHP_SECRET_SALT') != '') ? getenv('SIMPLESAMLPHP_SECRET_SALT') : 'defaultsecretsalt'),
    'auth.adminpassword' => ((getenv('SIMPLESAMLPHP_ADMIN_PASSWORD') != '') ? getenv('SIMPLESAMLPHP_ADMIN_PASSWORD') : 'secret'),
    'admin.protectmetadata' => false,
    'admin.checkforupdates' => true,
    'trusted.url.domains' => [],
    'trusted.url.regex' => false,
    'enable.http_post' => false,
    'assertion.allowed_clock_skew' => 180,
    'debug' => [
        'saml' => true,
        'backtraces' => true,
        'validatexml' => false,
    ],
    'showerrors' => true,
    'errorreporting' => true,
    'logging.level' => SimpleSAML\Logger::DEBUG,
    'logging.handler' => 'stderr',
    'logging.facility' => defined('LOG_LOCAL5') ? constant('LOG_LOCAL5') : LOG_USER,
    'logging.processname' => 'simplesamlphp',
    'logging.logfile' => 'simplesamlphp.log',
    'statistics.out' => [],
    'proxy' => null,
    'database.dsn' => 'mysql:host=localhost;dbname=saml',
    'database.username' => 'simplesamlphp',
    'database.password' => 'secret',
    'database.options' => [],
    'database.prefix' => '',
    'database.driver_options' => [],
    'database.persistent' => false,
    'database.secondaries' => [],
    'enable.saml20-idp' => true,
    'enable.adfs-idp' => false,
    'module.enable' => [
        'exampleauth' => true,
        'core' => true,
        'admin' => true,
        'saml' => true
    ],
    'session.duration' => 8 * (60 * 60), // 8 hours.
    'session.datastore.timeout' => (4 * 60 * 60), // 4 hours
    'session.state.timeout' => (60 * 60), // 1 hour
    'session.cookie.name' => 'SimpleSAMLSessionID',
    'session.cookie.lifetime' => 0,
    'session.cookie.path' => '/',
    'session.cookie.domain' => '',
    //'session.cookie.samesite' => $httpUtils->canSetSameSiteNone() ? 'None' : null,
    'session.phpsession.cookiename' => 'SimpleSAML',
    'session.phpsession.savepath' => null,
    'session.phpsession.httponly' => true,
    'session.authtoken.cookiename' => 'SimpleSAMLAuthToken',
    'session.rememberme.enable' => false,
    'session.rememberme.checked' => false,
    'session.rememberme.lifetime' => (14 * 86400),
    'memcache_store.servers' => [],
    'memcache_store.prefix' => '',
    'memcache_store.expires' => 36 * (60 * 60), // 36 hours.
    'language.available' => [
        'en', 'no', 'nn', 'se', 'da', 'de', 'sv', 'fi', 'es', 'ca', 'fr', 'it', 'nl', 'lb',
        'cs', 'sk', 'sl', 'lt', 'hr', 'hu', 'pl', 'pt', 'pt-br', 'tr', 'ja', 'zh', 'zh-tw',
        'ru', 'et', 'he', 'id', 'sr', 'lv', 'ro', 'eu', 'el', 'af', 'zu', 'xh', 'st',
    ],
    'language.rtl' => ['ar', 'dv', 'fa', 'ur', 'he'],
    'language.default' => 'en',
    'language.parameter.name' => 'language',
    'language.parameter.setcookie' => true,
    'language.cookie.name' => 'language',
    'language.cookie.domain' => '',
    'language.cookie.path' => '/',
    'language.cookie.secure' => true,
    'language.cookie.httponly' => false,
    'language.cookie.lifetime' => (60 * 60 * 24 * 900),
    'language.cookie.samesite' => $httpUtils->canSetSameSiteNone() ? 'None' : null,
    'theme.use' => 'default',
    'template.auto_reload' => false,
    'production' => false,
    'assets' => [
        'caching' => [
            'max_age' => 86400,
            'etag' => false,
        ],
    ],
    'idpdisco.enableremember' => true,
    'idpdisco.rememberchecked' => true,
    'idpdisco.validate' => true,
    'idpdisco.extDiscoveryStorage' => null,
    'idpdisco.layout' => 'dropdown',
    'authproc.idp' => [],
    'authproc.sp' => [],
    'metadatadir' => 'metadata',
    'metadata.sources' => $metadata_sources,
    'metadata.sign.enable' => false,
    'metadata.sign.privatekey' => null,
    'metadata.sign.privatekey_pass' => null,
    'metadata.sign.certificate' => null,
    'metadata.sign.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
    'store.type'                    => 'phpsession',
    'store.sql.dsn'                 => 'sqlite:/path/to/sqlitedatabase.sq3',
    'store.sql.username' => null,
    'store.sql.password' => null,
    'store.sql.prefix' => 'SimpleSAMLphp',
    'store.sql.options' => [],
    'store.redis.host' => 'localhost',
    'store.redis.port' => 6379,
    'store.redis.username' => '',
    'store.redis.password' => '',
    'store.redis.tls' => false,
    'store.redis.insecure' => false,
    'store.redis.ca_certificate' => null,
    'store.redis.certificate' => null,
    'store.redis.privatekey' => null,
    'store.redis.prefix' => 'SimpleSAMLphp',
    'store.redis.mastergroup' => 'mymaster',
    'store.redis.sentinels' => [],
    'proxymode.passAuthnContextClassRef' => false,
];
