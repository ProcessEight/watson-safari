# Watson Pathfinder

A tool which produces high-level documentation (e.g. Flowcharts) for common tasks, e.g. Adding products to basket, checking stock levels, etc.

This tool is intended to provide the answer to questions like 'What happens when you add a product to the basket?', 'What happens to stock levels during checkout?', etc.

This tool uses Xdebug Function Tracing to produce the trace files which it then parses.

See also https://github.com/ProcessEight/Watson-Code-Coverage-Experiments for embryonic experiments which use the Xdebug code coverage feature instead.

## Phase 1

The purpose of this phase is to expose the route taken through the code, without going into detail about the things like plugins, events or SQL queries which may be triggered along the way.

### Phase 2

In this phase, the tool should provide details about things like plugins, events or SQL queries which are triggered along the way.

The tool should be able to provide a trace of every class, method, SQL query run, and Magento source code (Layout handles, plugin, event).

## Thoughts

* Generate trace using Xdebug Profiler or similar
* Create a script to parse the trace file
* Pass in a Xdebug Profiler trace file
* Parse the trace, with the following aims:
    * Produce high-level overview of execution flow in HTML format
    * Produce high-level overview of execution flow in DOT graph format

## Considerations

- Tool should be able to be run on any codebase
- All functions above should be programmable, so charts, documentation, etc can be generated automatically without human intervention
