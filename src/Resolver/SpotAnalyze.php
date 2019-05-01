<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:57
 */

namespace JunkMan\Resolver;


/**
 * Class StreamAnalyze
 * @package JunkMan\Resolver
 */
class SpotAnalyze extends Analyze
{
    private static $var;
    private static $line;

    public static function setVar($var){
        self::$var = $var;
    }

    /**
     * @param mixed $line
     */
    public static function setLine($line)
    {
        self::$line = $line;
    }

    /**
     * @param $content
     * @return array
     */
    public static function index($content)
    {
        $type = '';
        if (is_string($content)) {
            $type = self::STR;
        }

        if (is_int($content)) {
            $type = self::INT;
        }

        if (is_float($content)) {
            $type = self::FLOAT;
        }

        if (is_array($content)) {
            $type = self::ARR;
        }

        if (is_object($content)) {
            $type = 'object';
            $content = self::explainObject($content);
        }

        return [
            'Variable' => self::$var,
            'Value' => $content,
            'Type' => $type,
            'Line' => self::$line
        ];
    }

    private static function explainObject($object)
    {
        $data = [];
        $reflection = new \ReflectionClass($object);
        $data['namespace'] = $reflection->getNamespaceName();
        $data['name'] = $reflection->getShortName();
        $data['file'] = $reflection->getFileName();

        $attribute = [];
        $attribute['isInstantiable'] = $reflection->isInstantiable();
        $attribute['isFinal'] = $reflection->isFinal();
        $attribute['isAbstract'] = $reflection->isAbstract();
        $attribute['isInterface'] = $reflection->isInterface();
        $attribute['isAnonymous'] = $reflection->isAnonymous();
        $attribute['isInterface'] = $reflection->isInterface();
        $attribute['isTrait'] = $reflection->isTrait();
        $data['attribute'] = $attribute;

        $data['traits'] = $reflection->getTraitNames();
        $data['interfaces'] = $reflection->getInterfaceNames();
        $data['constants'] = $reflection->getConstants();
        $data['static_properties'] = $reflection->getStaticProperties();
        $data['default_properties'] = $reflection->getDefaultProperties();

        $methods = [];
        $reflection_methods = $reflection->getMethods();
        foreach ($reflection_methods as $reflection_method){
            $method = [];
            $method['name'] = $reflection_method->getShortName();
            $method['abstract'] = $reflection_method->isAbstract();
            $method['final'] = $reflection_method->isFinal();
            $method['static'] = $reflection_method->isStatic();
            if($reflection_method->isPublic()){
                $method['attribute'] = 'public';
            }elseif ($reflection_method->isProtected()){
                $method['attribute'] = 'protected';
            }elseif ($reflection_method->isPrivate()){
                $method['attribute'] = 'private';
            }
            $methods[] = $method;
        }
        $data['methods'] = $methods;
        return $data;
    }

}