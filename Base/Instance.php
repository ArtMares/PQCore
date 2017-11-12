<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            10.11.2017
 */

namespace PQCore\Base;


class Instance
{
    /**
     * Экземпляр объекта
     * Instance Object
     */
    static protected $self = false;

    /**
     * Запрещаем инициализацию объекта из вне
     * Forbid Instance constructor
     */
    protected function __construct()
    {
    }

    /**
     * Запрещаем клонировать объект
     * Forbid clone object
     */
    protected function __clone()
    {
    }


    public static function &get()
    {
        $args = func_get_args();
        if (self::$self === false) {
            self::$self = new self($args);
        }
        return self::$self;
    }
}