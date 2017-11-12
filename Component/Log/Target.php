<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

namespace PQCore\Component\Log;


abstract class Target
{
    abstract public function export($msg);
}