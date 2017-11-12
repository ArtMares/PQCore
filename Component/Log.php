<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            12.11.2017
 */

namespace PQCore\Component;


use PQCore\Base\Component;
use PQCore\Base\Logger;
use PQCore\Component\Log\Dispatcher;
use PQCore\Component\Log\Target;
use PQCore\Core;

class Log extends Component implements Logger
{
    private $timerStart;

    private $randomSessionKey;

    private $dispatcher;

    public function __construct(Core $core)
    {
        parent::__construct($core);
        /**
         * Получаем время инициализации компонента
         * Get initialization timestamp
         */
        $this->timerStart = microtime(true);
        /**
         * Генерируем рандомный ключ
         * Generate random key
         */
        $this->randomSessionKey = rand(1,9) . substr($this->timerStart, 9, strlen($this->timerStart));
        $this->dispatcher = new Dispatcher();

        $this->info('->--- Session log start (session_id: ' . $this->randomSessionKey . ') ---<- ');
        $this->info('Component "' . $this->getShortName() . '" is loaded', 'Core');
    }

    /**
     * Метод задает таргет для записи логов
     * Method setting target for write logs
     * @param Target $target
     */
    public function setTarget(Target $target) {
        $this->dispatcher->setTarget($target);
    }

    public function info($msg, $itemName = false) {
        $this->log('Info', $msg, $itemName);
    }

    public function warning($msg, $itemName = false) {
        $this->log('Warning', $msg, $itemName);
    }

    public function error($msg, $itemName = false) {
        $this->log('Error', $msg, $itemName);
    }

    public function debug($msg, $itemName = false) {
        $this->log('Debug', $msg, $itemName);
    }

    private function log($level, $msg, $itemName = false) {
        $type = str_pad($level, 10, ' ');
        $time = date("Y-m-d H:i:s", time()) . ' ';
        $session = str_pad($this->randomSessionKey, 10, ' ');
        $msg = ' -> ' . $msg;
        if($itemName) {
            if(is_array($itemName)) {
                $itemName = implode(' -> ', $itemName);
            }
            $msg = ' -> ' . $itemName . $msg;
        }
        $message = $time . '| ' . $session . '| ' . $type . ':' . $msg;
        $this->dispatcher->export($message);
    }
}