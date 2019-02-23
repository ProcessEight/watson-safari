<?php
/**
 * @see https://derickrethans.nl/path-branch-coverage.html
 */

echo "Running auto_append_file" . PHP_EOL;

/**
 * @see https://xdebug.org/docs/code_coverage#xdebug_get_code_coverage
 */

$info = xdebug_get_code_coverage();

//var_export($info);

file_put_contents(
    '/var/www/html/watson/watson-safari/code-coverage-output.php',
    var_export($info, true)
);
