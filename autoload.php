<?php

/*
 * This file is part of the junkman package.
 *
 * (c) author <946818508@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/src/Autoloader.php';

if (!function_exists('xdebug_set_filter')) {
    throw new \Exception('Need to install Xdebug version >= 2.6');
}
JunkMan\Autoloader::register();
