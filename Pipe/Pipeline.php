<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/3
 * Time: 1:14
 */

try {
    $I = (string)current(getopt('I:'));
    $P = (string)current(getopt('P:'));
    $FILE = (string)current(getopt('F:'));
    $HEAD = (string)current(getopt('H:'));

    require_once "Socket.php";
    (new \Pipe\Socket($I, $P))->setHead($HEAD)->write($FILE);
} catch (Exception $e) {
    unlink($FILE);
    exit(0);
} finally {
    unlink($FILE);
    exit(0);
}