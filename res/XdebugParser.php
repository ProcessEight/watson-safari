<?php declare(strict_types=1);

/**
 * Class XdebugParser
 */
class XdebugParser
{
    /**
     * @var bool|resource
     */
    protected $handle;

    /**
     * @var array
     */
    protected $functions = [];

    /**
     * XdebugParser constructor.
     *
     * @param string $fileName
     *
     * @throws Exception
     */
    public function __construct($fileName)
    {
        $this->handle = fopen($fileName, 'r');
        if (!$this->handle) {
            throw new Exception("Can't open '$fileName'");
        }
    }

    /**
     * parse()
     */
    public function parse()
    {
        while (!feof($this->handle)) {
            $buffer = fgets($this->handle, 4096);
            if ($buffer !== false) {
                $this->parseLine($buffer);
            }
        }
    }

    /**
     * @param $line
     */
    public function parseLine($line) : void
    {
        $parts = explode("\t", $line);

        switch (@$parts[2]) {
            case '0': // Function enter
                $this->functions[$parts[1]]['depth']      = (int)$parts[0];
                $this->functions[$parts[1]]['time.enter'] = $parts[3];
                $this->functions[$parts[1]]['name']       = $parts[5];
                $this->functions[$parts[1]]['internal']   = $parts[6];
                $this->functions[$parts[1]]['file']       = $parts[8];
                $this->functions[$parts[1]]['line']       = $parts[9];
                if ($parts[7]) {
                    $this->functions[$parts[1]]['params'] = [$parts[7]];
                }

                // these are set later // Necessary to do this here?
                $this->functions[$parts[1]]['return'] = '';
                break;
            case '1': // Function exit
                $this->functions[$parts[1]]['time.exit'] = $parts[3];
                $this->functions[$parts[1]]['time.diff'] = $this->functions[$parts[1]]['time.exit'] - $this->functions[$parts[1]]['time.enter'];
                break;
            case 'R'; // Function return
                $this->functions[$parts[1]]['return'] = $parts[5];
                break;
        }
    }

    /**
     * @return array
     */
    public function getTrace()
    {
        return $this->functions;
    }

    /**
     * @todo Add background color for each new depth level
     *
     * @return string
     */
    public function getTraceHTML()
    {
        ob_start();

        echo '<div class="f header">';
        echo '<div class="func">Function Call</div>';
        echo '<div class="data">';
        echo '<span class="file">File:Line</span>';
        echo '</div>';
        echo '</div>';

        $level           = 0;
        $stripeClassName = '';
        foreach ($this->functions as $func) {
            // depth wrapper
            if ($func['depth'] > $level) {
                for ($i = $level; $i < $func['depth']; $i++) {
                    echo '<div class="d">';
                }
            } else if ($func['depth'] < $level) {
                for ($i = $func['depth']; $i < $level; $i++) {
                    echo '</div>';
                }
            }
            $level = $func['depth'];

            echo '<div class="f ' . $stripeClassName . '">';
            echo '<div class="func">';
            $internalClassName = ($func['internal'] === "0") ? "internal" : "";
            echo '<span class="name ' . $internalClassName . '">' . htmlspecialchars($func['name']) . '</span>';
            echo '</div>';
            echo '<div class="data">';
            echo '<span class="file" title="' . htmlspecialchars($func['file'] . ':' . $func['line']) . '">' . htmlspecialchars(basename($func['file']) . ':' . $func['line']) . '</span>';
            echo '</div>';
            echo '</div>';

            $stripeClassName = ($stripeClassName == '') ? 'stripe' : '';
        }

        if ($level > 0) {
            for ($i = 0; $i < $level; $i++) {
                echo '</div>';
            }
        }

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
