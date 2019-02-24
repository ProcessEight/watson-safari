<?php declare(strict_types=1);
/**
 * ProcessEight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @category    watson-safari
 * @package     code-coverage-to-html.php
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

/**
 * Run it: php71 -f code-coverage-to-html.php
 */

require "code-coverage-to-html-parser.php";

eval(file_get_contents('code-coverage-output.php'));

/**
 * Convert the code coverage results array into a HTML page
 */
file_put_contents(
    '/var/www/vhosts/watson/watson-safari/execution-path.html',
    code_coverage_to_html($info)
);
