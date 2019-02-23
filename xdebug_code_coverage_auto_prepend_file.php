<?php
/**
 * @see https://derickrethans.nl/path-branch-coverage.html
 */

echo "Running auto_prepend_file" . PHP_EOL;

/**
 * @see https://xdebug.org/docs/all_functions#xdebug_set_filter
 */
xdebug_set_filter(
    XDEBUG_FILTER_CODE_COVERAGE,
    XDEBUG_PATH_WHITELIST,
    [
        '/var/www/html/magento225-example-modules/htdocs/app/',
        '/var/www/html/magento225-example-modules/htdocs/generated/',
        '/var/www/html/magento225-example-modules/htdocs/vendor/magento/',
        '/var/www/html/magento225-example-modules/htdocs/vendor/symfony/',
    ]
);

/**
 * @see https://xdebug.org/docs/code_coverage#xdebug_start_code_coverage
 */
xdebug_start_code_coverage(
    XDEBUG_CC_DEAD_CODE |
    XDEBUG_CC_UNUSED |
    XDEBUG_CC_BRANCH_CHECK
);
