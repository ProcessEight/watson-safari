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
     * @var string[]
     */
    private $colours = [];

    /**
     * @var array
     */
    protected $functions = [];

    private $traceFormatFunctionEnterKeys = [
        'level',
        'function_number',
        'record_type',
        'time_index',
        'memory_usage',
        'function_name',
        'user_defined_or_internal',
        'include_require_filename',
        'filename',
        'line_number',
    ];

    private $traceFormatFunctionExitKeys = [
        'level',
        'function_number',
        'record_type',
        'time_index',
        'memory_usage',
    ];

    private $traceFormatFunctionReturnKeys = [
        'level',
        'function_number',
        'record_type',
        'EMPTY_1',
        'EMPTY_2',
        'return_value',
    ];

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
        $this->colours = $this->initialiseRainbow();
    }

    /**
     * parse()
     */
    public function parse()
    {
        while (!feof($this->handle)) {
            $buffer = fgets($this->handle);
            if ($buffer !== false) {
                $this->parseLine($buffer);
            }
        }
    }

    /**
     * Parse the Xdebug execution trace.
     * See https://xdebug.org/docs/all_settings#trace_format for a description of what each element of the parts array represents
     *
     * @param $line
     */
    public function parseLine($line) : void
    {
        $parts = explode("\t", $line);
        // Lines of precisely 5 parts represent the 'exit' record type
        // Lines of longer than 5 parts represent the 'enter' record type
        // Anything else should be ignored
        // Filter out calls to PHP internal functions
        // $parts[6]: user-defined (1) or internal function (0)
        if (count($parts) < 5) {
            return;
        }

        switch ($parts[2]) { // $parts[2]
            case '0':
                // ENTRY
                $data = array_combine($this->traceFormatFunctionEnterKeys, $parts);

                $this->functions[] = $data;
                break;
            case '1':
                // EXIT
                $data = array_combine($this->traceFormatFunctionExitKeys, $parts);

                $this->functions[] = $data;
                break;
            case 'R`':
                // RETURN
                $data = array_combine($this->traceFormatFunctionReturnKeys, $parts);

                $this->functions[] = $data;
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
     */
    public function getTraceHTML() : string
    {
        $output = '';
        foreach ($this->functions as $stackFrame) {
            $output .= $this->renderFrame($stackFrame);
        }

        return $output;
    }

    /**
     * @param $frameData
     *
     * @return string
     */
    private function renderFrame($frameData) : string
    {
        $colour = $this->colours[(int)$frameData['level']];
        $renderedFrame = '';
        switch ($frameData['record_type']) {
            case '0':
                $renderedFrame .= '<!-- ENTER ' . $frameData['function_number'] . ' -->';
                $renderedFrame .= '<div class="func" style="border: 1px solid ' . $colour . '; margin: 5px; padding: 5px;">' . $frameData['function_name'];
                break;
            case '1':
                $renderedFrame .= '<!-- EXIT ' . $frameData['function_number'] . ' -->';
                $renderedFrame .= '</div>';
                break;
            case 'R':
                $renderedFrame .= '<!-- RETURN ' . $frameData['function_number'] . ' -->';
                $renderedFrame .= '</div>';
                break;
        }

        return $renderedFrame;
    }

    /**
     * @param $function
     *
     * @return string
     */
    private function getFilename($function) : string
    {
        $filename = basename($function['filename']);
        if ($filename === 'Interceptor.php') {
            $filenameParts     = explode('/', $function['filename']);
            $interceptFilename = array_pop($filenameParts);
            $nextPart          = array_pop($filenameParts);
            $filename          = $nextPart . DIRECTORY_SEPARATOR . $interceptFilename;
        }

        return $filename;
    }

    /**
     * @return array
     */
    private function initialiseRainbow() : array
    {
        $colours = [
            'red',
            'orange',
            'yellow',
            'green',
            'blue',
            'indigo',
            'violet',
            'cyan',
            'magenta',
            'teal',
            'grey',
        ];

        $roygbiv = [];
        foreach ($colours as $key => $colour) {
            $roygbiv = $roygbiv + array_fill_keys(range($key, 90 + $key, 10), $colour);
        }
        ksort($roygbiv);
        $this->colours = $roygbiv;

        return $roygbiv;
    }
}
