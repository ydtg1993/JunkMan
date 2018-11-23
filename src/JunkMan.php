<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22 0022
 * Time: 下午 2:27
 */

namespace JunkMan;

use JunkMan\Operation\OperateSpot;
use JunkMan\Operation\OperateStream;

class JunkMan
{
    const ROOT_PATH = __DIR__;

    public static function stream()
    {
        return OperateStream::getInstance();
    }

    public static function spot()
    {
        return new OperateSpot();
    }
}