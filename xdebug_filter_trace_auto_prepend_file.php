<?php

/**
 * @see https://xdebug.org/docs/all_functions#xdebug_set_filter
 */
xdebug_set_filter(
    XDEBUG_FILTER_TRACING,
    XDEBUG_PATH_WHITELIST,
    [
        '/var/www/html/magento225-example-modules/htdocs/app/',
        '/var/www/html/magento225-example-modules/htdocs/bin/',
        '/var/www/html/magento225-example-modules/htdocs/generated/',
        '/var/www/html/magento225-example-modules/htdocs/lib/',
        '/var/www/html/magento225-example-modules/htdocs/pub/',
        '/var/www/html/magento225-example-modules/htdocs/setup/',
        '/var/www/html/magento225-example-modules/htdocs/update/',
        '/var/www/html/magento225-example-modules/htdocs/var/',
        '/var/www/html/magento225-example-modules/htdocs/vendor/magento/',
    ]
);
