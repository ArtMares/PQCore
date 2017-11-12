<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

namespace PQCore\Base;


use PQCore\Component\Log\Target;

interface Logger
{
    public function setTarget(Target $target);

    public function info($msg, $itemName = false);

    public function warning($msg, $itemName = false);

    public function error($msg, $itemName = false);

    public function debug($msg, $itemName = false);
}