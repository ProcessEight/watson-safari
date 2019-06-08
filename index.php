<?php declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 'true');
?>
<?php
$dir = ini_get('xdebug.trace_output_dir') ?: '/var/www/html/watson/watson-pathfinder/xdebug-trace-files';
?>
<!doctype html>
<html lang="en_gb">
    <head>
        <title>Watson: Pathfinder</title>
        <link rel="stylesheet" href="res/style.css">
    </head>
    <body>
        <p>Reading files from <code><?= htmlspecialchars($dir) ?></code></p>
        <form method="post" class="load">
            <p>
                <label for="file">File:</label>
                <select name="file" id="file">
                <?php
                $files = glob("$dir/*");
                foreach ($files as $file) :
                    echo '<option value="' . htmlspecialchars($file) . '">' . htmlspecialchars(basename($file)) . '</option>';
                endforeach;
                ?>
                </select>
            </p>
            <p>Options:</p>
            <div>
                <input type="checkbox" id="filter_php_core_functions" name="filter_php_core_functions">
                <label for="filter_php_core_functions">Filter PHP core functions</label>
            </div>
            <div>
                <input type="checkbox" id="horns" name="horns">
                <label for="horns">Horns</label>
            </div>
            <p>
                <input type="submit" name="submit">Load</input>
            </p>
        </form>
        <?php
        if (!empty($_REQUEST['file'])) :
            require_once 'res/XdebugParser.php';
            $parser = new XdebugParser($_REQUEST['file'] ?? null);
            $parser->parse();
            echo $parser->getTraceHTML();
        endif;
        ?>
    </body>
</html>
