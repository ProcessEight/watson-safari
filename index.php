<?php declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 'true');
?>
<?php
$dir = ini_get('xdebug.trace_output_dir') ?: '/var/www/html/watson/watson-pathfinder/xdebug-trace-files/';
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
                    $checked = ($file == $_REQUEST['file']) ? 'selected="selected"' : '';
                    echo '<option value="' . htmlspecialchars($file) . '" ' . $checked . '>' . htmlspecialchars(basename($file)) . '</option>';
                endforeach;
                ?>
                </select>
                <button type="submit">Load</button>
            </p>
        </form>
        <?php
        if (!empty($_REQUEST['file'])) :
            require_once 'res/XdebugParser.php';
            $parser = new XdebugParser($_REQUEST['file']);
            $parser->parse();
            echo $parser->getTraceHTML();
        endif;
        ?>
    </body>
</html>
