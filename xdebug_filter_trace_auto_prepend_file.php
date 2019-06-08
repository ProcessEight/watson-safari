<?php

/**
 * @see https://xdebug.org/docs/all_functions#xdebug_set_filter
 */
xdebug_set_filter(
    XDEBUG_FILTER_TRACING,
    XDEBUG_PATH_BLACKLIST,
    [
//        '/var/www/html/m2-research/m23-example-modules/htdocs/app/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/bin/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/generated/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/lib/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/pub/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/setup/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/update/',
//        '/var/www/html/m2-research/m23-example-modules/htdocs/var/',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/composer',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/framework',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/language',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/magento-composer-installer',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/magento-functional-testing-framework',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/theme',
        '/var/www/html/m2-research/m23-example-modules/htdocs/vendor/magento/zendframework1',
    ]
);
