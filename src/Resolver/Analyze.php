<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/20 0020
 * Time: 上午 10:08
 */
namespace JunkMan\Resolver;

/**
 * Class Analyze
 * @package JunkMan\Resolver
 */
abstract class Analyze
{
    const at = '=>';
    const start_mark = 'TRACE START';
    const end_mark = 'TRACE END';

    const BOOLEAN = 'boolean';
    const INT = 'integer';
    const FLOAT = 'float';
    const STR = 'string';
    const ARRAY = 'array';
    const CLA = 'stdClass';
    const UNDEFINED = 'undefined';

    /**
     * @param $content
     * @return mixed
     */
    public static abstract function index($content);
}