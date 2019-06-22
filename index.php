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
        <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
        <script src="res/script.js"></script>
    </head>
    <body>
        <div style="clear: both">
            <form method="post" class="load">
                <label for="file">File:</label>
                <select name="file" id="file">
                    <?php
                    $files = glob("$dir/*");
                    foreach ($files as $file) :
                        echo '<option value="' . htmlspecialchars($file) . '">' . htmlspecialchars(basename($file)) . '</option>';
                    endforeach;
                    ?>
                </select>
                <button type="submit">Load</button>
                <br/>

                <p>Files are read from <code>xdebug.trace_output_dir = <?php echo htmlspecialchars($dir) ?></code></p>
                <p>Note that you may need to remove opening and closing lines from the tracefile, in order for the divs to be rendered correctly, especially if the tracefile starts with an EXIT (1) record type or ends with a ENTER (0) record type.</p>
            </form>

            <ul class="help">
                <li>load a trace file from the dropdown</li>
                <li>TODO: click a left margin to collapse a whole sub tree</li>
                <li>TODO: click a function name to collapse all calls to the same function</li>
                <li>TODO: click the parameter list to expand it</li>
                <li>TODO: click the return list to expand it</li>
            </ul>

        </div>

        <div class="options" style="clear: both;">
            <!-- Opening function trace wrapper div -->
            <?php
            if (!empty($_REQUEST['file'])) :
                require_once 'res/XdebugParser.php';
                $parser = new XdebugParser($_REQUEST['file'] ?? null);
                $parser->parse();
                echo $parser->getTraceHTML();
            endif;
            ?>
            <!-- Closing function trace wrapper div -->
        </div>
    </body>
</html>
