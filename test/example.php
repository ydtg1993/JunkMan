<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/27 0027
 * Time: 下午 5:55
 */
require_once __DIR__.'/../autoload.php';
use JunkMan\JunkMan;
//测试调用
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
    //测试代码
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
    //测试代码
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
    //测试代码
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
    //测试代码
    $a = 1 / 0;
    JunkMan::stream()->end();
}
