<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

# QDir
# QFile
# QFileDevice
# QIODevice
# QObject

namespace PQCore\Component\Log\Target;


use PQCore\Component\Log\Target;

class File extends Target
{
    private $ext = '.log';

    private $path;

    private $qf = false;

    public function __construct($path)
    {
        $this->path = $path;
        $dir = new \QDir($this->path);
        $dir->mkpath($this->path);
        $this->qf->setFileName($this->path.'/'.date('Y-m-d', time()).$this->ext);
        unset($dir);
    }

    public function export($msg)
    {
        if($this->qf->open(\QIODevice::Append)) {
            $this->qf->write($msg);
            $this->qf->close();
        }
    }
}