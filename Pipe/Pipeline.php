<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/3
 * Time: 1:14
 */

//unlink("D:\www\blog\app\libs\Stream\Pipe\g.txt");
$params = file_get_contents('php://stdin');

file_put_contents("D:\www\blog\app\libs\Stream\Pipe\g.txt",111);