<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */

namespace Pipe;

use Core\Defined;
use Mockery\Exception;

class Driver
{
    public static function execute()
    {
        $config = Defined::getINI();
        $file = Defined::getTemp() . '.xt';
        try {
            (new Socket($config['SERVER'], $config['PORT']))->write($file);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }
}