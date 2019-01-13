<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/27 0027
 * Time: 下午 5:55
 */
require_once __DIR__.'/../autoload.php';
use JunkMan\JunkMan;
test1();
//test2();
//test3();
//test4();


/**
 * stream
 * @throws \JunkMan\E\OperateException
 */
function test1()
{
    JunkMan::stream()->start('stream test');
    //test code block on stream
    $array = [1, 2, 3, 4];
    $total = 0;
    foreach ($array as $a) {
        $total = $total + $a;
    }
    JunkMan::stream()->end();
}

/**
 * flush
 * @throws \JunkMan\E\OperateException
 */
function test2()
{
    JunkMan::flush()->start('flush test');
    //test code block on flush
    $i = 1;
    $data = [];
    while($i <= 3){
        $data[] = $i;
        JunkMan::flush()->refurbish();
        sleep(1);
        $i++;
    }
    JunkMan::flush()->end();
}

/**
 * dot
 * @throws \JunkMan\E\OperateException
 */
function test3()
{
    //test code block on dot
    $data = [1,2,3,4,5];
    JunkMan::spot()->dot('dot test',$data);
}

/**
 * error
 * @throws \JunkMan\E\OperateException
 */
function test4()
{
    JunkMan::stream()->start('stream test');
    //test code block on error
    $a = 1 / 0;
    JunkMan::stream()->end();
}
