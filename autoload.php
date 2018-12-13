<?php

/*
 * This file is part of the junkman package.
 *
 * (c) author Hikki <946818508@qq.com>
 * monitor data stream of code segment
 */

require __DIR__.'/src/Autoloader.php';

if (!function_exists('xdebug_set_filter')) {
    throw new \Exception('Need to install Xdebug version >= 2.6.0');
}
JunkMan\Autoloader::register();
