# A simple MVC [VC] framework [![Build Status](https://secure.travis-ci.org/wdalmut/simple-mvc.png)](http://travis-ci.org/wdalmut/simple-mvc?branch=master)

A simple MVC framework heavly inspired to different PHP microframeworks and
PHP MVC framework like ZF1.

## Why?

I want to try out the test-driven development [at least write some tests ;)].

Just for my pleasure.

## Goals

 * PHPUnit
 * All classes must be less than 100 lines of code
 * Very simple implementation (only 6 classes + autoloader)
 * PHP 5.3+ implementation
 
## Features

 * 100% MVC implementation [66% no model support] ;)
 * Useful hooks (Fixed events)
  * Loop Startup
  * Pre Dispatch
  * Init Hook
  * Post Dispatch
  * Loop Shutdown
 * Two step view (Layout support)
 * Controllers stack
 * Headers handler
 * Event manager (Self designed hooks)
 * Router
  * Only controller/action names