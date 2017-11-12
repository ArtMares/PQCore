<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

namespace PQCore\Base;


use PQCore\Core;

/**
 * Class Component
 * @package PQCore\Base
 * @property-read       Core        $core
 */
class Component
{
    protected $core;

    private $componentName;

    public function __construct(Core &$core)
    {
        $this->core = $core;
        $this->componentName = $this->getShortName();
        if(isset($this->core->log) && $this->core->log !== false) {
            $this->core->log->info('Component "' . $this->componentName . '" is loaded', 'Core');
        }
    }

    public function __destruct() {
        $this->_log('Component "'.$this->componentName.'" is destruct', 'Core', 'info');
    }

    protected function getShortName() {
        $reflector = new \ReflectionClass($this);
        return $reflector->getShortName();
    }
}