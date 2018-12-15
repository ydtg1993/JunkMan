<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:31
 */

namespace JunkMan\E;

/**
 * Class IoException
 * @package JunkMan\E
 */
class IoException extends \Exception
{
    public function __toString()
    {
        return __CLASS__ . "{$this->getFile()}: {$this->getLine()} : {$this->message}\n";
    }
}