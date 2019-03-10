# Watson Pathfinder

A tool which produces high-level documentation (e.g. Flowcharts) for common tasks, e.g. Adding products to basket, checking stock levels, etc.

This tool is intended to provide the answer to questions like 'What happens when you add a product to the basket?', 'What happens to stock levels during checkout?', etc

This tool uses Xdebug Function Tracing to produce the trace files which it can then parse.

See also https://github.com/ProcessEight/Watson-Code-Coverage-Experiments for embryonic experiments which use the Xdebug code coverage feature instead.

The 'Tools' section has been moved to https://github.com/ProcessEight/Watson 

## Thoughts

* Generate trace using Xdebug Profiler or similar
* Create a new `bin/magento` command
* Pass in a Xdebug Profiler tracefile
* Parse the trace, with the following aims:
    * Highlight extension points (public methods (for plugins), events)
    * Produce customisation reports:
        * Produce report of all third-party extensions (i.e. Extensions not present in a stock Magento install)
            * Where they are called
            * How the logic is invoked (i.e. `Event`, `plugin`, `preference`, etc)
            * What they are doing
        * All events
            * Those which were dispatched
                * Document the module which defines the `observer`
                * Document the event name, variables passed in
            * Those which were not
                * Document the event name, variables passed in
        * All defined plugins
        * All preferences where a Magento core interface is implemented by a non-Magento class
    * Produce high-level overview of execution flow (possibly in form of flowchart, or just plain text description)
    * Whenever a new class is called, or a class outside the current module is called, add a new element to the flow chart

## Considerations

- Tool should be able to be run on any codebase, so the above can be generated
- All functions above should be programmable, so charts, documentation, etc can be generated automatically without human intervention
- Added a switch to toggle between generating DOT graphs and HTML table
    - For the HTML version, link each line number to the line number of the actual file, otherwise it'll be much harder to interpret what the table is showing

## Overview

We are not interested in any third-party libraries, or tools used in the intialisation of the request we're tracing, so these are filtered out by Xdebug.

The filtering is configured in `xdebug_filter_trace_auto_prepend_file.php`.

The filtering using a whitelisting approach, so only files in Magento-specific directories are added to the trace file.
