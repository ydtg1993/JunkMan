<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:11
 */
namespace JunkMan\Abstracts;

/**
 * Class Singleton
 * @package JunkMan\Abstracts
 */
abstract class Singleton
{
    private static $_instances;

    private function __construct($data = null)
    {
        $this->execute($data);
    }

    /**
     * @param null $data
     * @return mixed
     */
    public static function getInstance($data = null)
    {
        if (!isset(self::$_instances[get_called_class()]) || !self::$_instances[get_called_class()] instanceof self) {
            self::$_instances[get_called_class()] = new static($data);
        }

        return self::$_instances[get_called_class()];
    }

    /**
     * @param null $data
     * @return mixed
     */
    protected abstract function execute($data = null);

    private function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }
}