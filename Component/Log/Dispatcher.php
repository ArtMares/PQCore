<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

namespace PQCore\Component\Log;


class Dispatcher
{
    /** @var Target[] */
    private $targets = [];

    public function setTarget(Target $target) {
        $this->targets[] = $target;
    }

    public function export($msg) {
        foreach($this->targets as $target) {
            $target->export($msg);
        }
    }
}