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
     * Parse the Xdebug execution trace.
     * See https://xdebug.org/docs/execution_trace#trace_format for a description of what each element of the parts array represents
     *
     * @param $line
     */
    public function parseLine($line) : void
    {
        $parts = explode("\t", $line);

        $depth                 = $parts[0] ?? null;
        $functionNumber        = $parts[1] ?? null;
        $recordType            = $parts[2] ?? null;
        $timeEntered           = $parts[3] ?? null;
        $memoryUsage           = $parts[4] ?? null;
        $functionName          = $parts[5] ?? null;
        $userDefinedOrInternal = $parts[6] ?? null; // User defined (1) or internal (0)
        $params                = $parts[7] ?? null;
        $filename              = $parts[8] ?? null;
        $lineNumber            = $parts[9] ?? null;

        $skipList = ['include', 'include_once', 'require', 'require_once',];
        if ($userDefinedOrInternal == 0 || in_array($functionName, $skipList)) {
            return;
        }
        if (strpos($filename,'Interceptor') !== false) {
            return;
        }
        if (strpos($filename,'Composer') !== false) {
            return;
        }

        switch (@$recordType) {
            case '0': // Function enter
                $this->functions[$functionNumber]['depth']      = (int)$depth;
                $this->functions[$functionNumber]['time.enter'] = $timeEntered;
                $this->functions[$functionNumber]['name']       = $functionName;
                $this->functions[$functionNumber]['internal']   = $userDefinedOrInternal;
                $this->functions[$functionNumber]['file']       = $filename;
                $this->functions[$functionNumber]['line']       = $lineNumber;
                if ($params) {
                    $this->functions[$functionNumber]['params'] = [$params];
                }

                // these are set later // Necessary to do this here?
                $this->functions[$functionNumber]['return'] = '';
                break;
            case '1': // Function exit
                $this->functions[$functionNumber]['time.exit'] = $timeEntered;
                $this->functions[$functionNumber]['time.diff'] = $this->functions[$functionNumber]['time.exit'] - $this->functions[$functionNumber]['time.enter'];
                break;
            case 'R'; // Function return
                $this->functions[$functionNumber]['return'] = $functionName;
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
     * @return string
     * @todo Add background color for each new depth level
     *
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
            echo '<span class="name ' . (($func['internal'] === "0") ? "internal" : "") . '">' . htmlspecialchars($func['name']) . '</span>';
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
