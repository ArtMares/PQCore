<?php
/**
 * @author          Dmitriy Dergachev (ArtMares)
 * @date            10.11.2017
 */

namespace PQCore;


use PQCore\Base\EmptyLog;
use PQCore\Base\Instance;

/**
 * Class Core
 * @package PQCore
 * @property        bool            $WIN
 * @property        object          $QApp
 * @property        string          $PATH
 * @property        string          $QT_PATH
 * @property        string          $APP_PATH
 * @property        string          $APP_DATA
 * @property        string          $HOME_PATH
 */
class Core extends Instance
{
    /**
     * Типы приложений
     * Applications Type
     */
    const QtCore = 0x00;
    const QtGui  = 0x01;
    const QtApp  = 0X02;
    /**
     * Сегменты версии
     * Version Segments
     */
    const MAJOR_VERSION     = 0;
    const MINOR_VERSION     = 1;
    const RELEASE_VERSION   = 1;
    /**
     * Версия ядра
     * Core Version
     */
    const VERSION = self::MAJOR_VERSION.'.'.self::MINOR_VERSION.'.'.self::RELEASE_VERSION;
    /**
     * Объект QApplication
     * Object QApplication
     */
    public $QApp;
    /**
     * Путь к каталогу приложения
     * The path to the application directory
     */
    public $APP_PATH;
    /**
     * Путь к AppData приложения в директории пользователя
     * The path to the application in the user's AppData directory
     */
    public $APP_DATA;
    /**
     * Путь к домашней директории пользователя
     */
    public $HOME_PATH;
    /**
     * Путь к каталогу в котором расположенно ядро
     * The path to the directory in which the location of the Core
     */
    public $PATH;
    /**
     * Путь к каталогу в котором расположенно ядро для объектов Qt
     * The path to the directory in which the location of the Core for Qt objects
     */
    public $QT_PATH;
    /**
     * ОС семейства Windows
     * Windows operating systems
     */
    public $WIN = false;
    /**
     * Список компонентов ядра
     * The list of Core components
     */
    private $components = [
        self::QtCore => [
            'log' => 'Log', 'variant' => 'Variant', 'var' => 'Variable', 'config' => 'Config',
            'dir' => 'Dir', 'file' => 'File', 'lib' => 'Lib', 'storage' => 'Storage',
            'single' => 'Single', 'process' => 'Process'
        ],
        self::QtGui => [
            'font' => 'Font'
        ],
        self::QtApp => [
            'style' => 'Style', 'icon' => 'Icon'
        ],
    ];

    protected function __construct($type = self::QtApp)
    {
        global $argc, $argv;
        if((!class_exists('QApplication') || !class_exists('QCuiApplication') || !class_exists('QCoreApplication')) && !class_exists('QStandardPaths')) {
            die('Error! Core not run!' . PHP_EOL . 'Please assemble the project using QCoreApplication and QStandardPaths of Core library');
        }
        $this->WIN = stripos(PHP_OS, 'win') === false ? false : true;
        switch((int)$type) {
            case self::QtCore:
                $this->QApp = new \QCoreApplication($argc, $argv);
                break;
            case self::QtGui:
                $this->QApp = new \QGuiApplication($argc, $argv);
                break;
            case self::QtApp:
                $this->QApp = new \QApplication($argc, $argv);
                break;
            default:
                die('Error! Core not run!' . PHP_EOL . 'Please specify the type of application!' . PHP_EOL . 'Use constants: PQCore::QtApp or PQCore::QtGui or PQCore::QtCore');
        }
        $this->PATH = __DIR__.'/';
        $this->QT_PATH = stripos($this->PATH, 'qrc://') === false ? $this->PATH : str_replace('qrc://', ':/', $this->PATH);
        $this->APP_PATH = \QCoreApplication::applicationDirPath().'/';
        /** Проверяем существует ли файл конфигурации */
        if(\QFile::exists(':/pqcore.config.php')) {
            /** Если да то подключаем его и получаем конфигурационный конфиг */
            $app_config = require_once('qrc://pqcore.config.php');
            /** Проходим по конфигурационному массиву и задаем основные данные о приложении */
            foreach($app_config as $name => $value) {
                if(in_array($name, ['applicationName', 'applicationVersion', 'organizationName', 'organizationDomain'])) {
                    $this->{$name}($value);
                }
            }
            /** Получаем путь к AppData приложения в директории пользователя */
            $this->APP_DATA = \QStandardPaths::writableLocation(\QStandardPaths::AppLocalDataLocation).'/';

            $this->HOME_PATH = \QStandardPaths::writableLocation(\QStandardPaths::HomeLocation).'/';
        }
        require_once $this->PATH . 'Component.php';
        $components = [];
    }

    public function getPath($qt = false) {
        if((bool)$qt) {
            return $this->QT_PATH;
        }
        return $this->PATH;
    }

    public function getLogger() {
        if(isset($this->log) && $this->log !== false) {
            return $this->log;
        }
        return new EmptyLog($this);
    }

    /**
     * Задает или возвращает название приложения
     * Sets or return the name of application
     * @param bool $string
     * @return mixed
     */
    public function applicationName($string = false) {
        if($string) {
            $this->QApp->applicationName = $string;
            return null;
        }
        return $this->QApp->applicationName;
    }

    /**
     * Задает или возвращает версию приложения
     * Sets or return a version of application
     * @param bool $string
     * @return mixed
     */
    public function applicationVersion($string = false) {
        if($string) {
            $this->QApp->applicationVersion = $string;
            return null;
        }
        return $this->QApp->applicationVersion;
    }

    /**
     * Задает или возвращает название организации
     * Sets or return the name of organization
     * @param bool $string
     * @return mixed
     */
    public function organizationName($string = false) {
        if($string) {
            $this->QApp->organizationName = $string;
            return null;
        }
        return $this->QApp->organizationName;
    }

    /**
     * Задает или возвращает домен организации
     * Sets or return the organization domain
     * @param bool $string
     * @return mixed
     */
    public function organizationDomain($string = false) {
        if($string) {
            $this->QApp->organizationDomain = $string;
            return null;
        }
        return $this->QApp->organizationDomain;
    }
}