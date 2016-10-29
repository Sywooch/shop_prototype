<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;

class TestsController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * @var string ID компонента базы данных, подключенного в файле конфигурации
     */
    public $db = 'dbTest';
    /**
     * @var string имя профиля, созданного коммандой mysql_config_editor
     */
    public $loginPath = 'yii';
    /**
     * @var string имя тестовой БД, которая будет создана
     */
    public $testDbName = 'shop_test';
    /**
     * @var int код возврата exec
     * - 0 в случае успешного выполнения
     * - 1 в случае некритичных ошибок
     * - 2 в случае критичных системных ошибок
     */
    private $_returnCode;
    /**
     * @var массив будет заполнен строками вывода программы
     */
    private $_outputArray = [];
    
    public function init()
    {
        try {
            parent::init();
            
            $this->escapeArgs();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function options($actionID)
    {
        try {
            return ['db', 'loginPath', 'testDbName'];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Создает БД, применяет миграции
     * @return int
     */
    public function actionSet()
    {
        try {
            $this->stdout(\Yii::t('base/console', 'Create database {database}...' . PHP_EOL, ['database'=>$this->testDbName]));
            
            $cmd = sprintf("mysql --login-path=%s -e 'CREATE DATABASE IF NOT EXISTS %s'", $this->loginPath, $this->testDbName);
            exec($cmd, $this->_outputArray, $this->_returnCode);
            if ($this->_returnCode != 0) {
                throw new ErrorException(\Yii::t('base/console', 'Method error {placeholder}!', ['placeholder'=>'CREATE DATABASE $this->testDbName']));
            }
            
            $this->stdout(\Yii::t('base/console', 'Apply migrations...' . PHP_EOL, ['database'=>$this->testDbName]));
            
            $cmd = sprintf("/var/www/html/shop/yii migrate --db=%s --interactive=0", $this->db);
            exec($cmd, $this->_outputArray, $this->_returnCode);
            if ($this->_returnCode != 0) {
                throw new ErrorException(\Yii::t('base/console', 'Method error {placeholder}!', ['placeholder'=>'Apply migrations']));
            }
            
            $this->stdout(\Yii::t('base/console', 'Database {database} created successfully, migrations applied!' . PHP_EOL, ['database'=>$this->testDbName]));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Yii::t('base/console', 'Error creating database {database}!' . PHP_EOL, ['database'=>$this->testDbName]) . $t->getMessage() . '' . PHP_EOL, Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Откатывает миграции, удаляет БД
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', 'Erase migrations...' . PHP_EOL, ['database'=>$this->testDbName]));
            
            $cmd = sprintf("/var/www/html/shop/yii migrate/down --db=%s --interactive=0", $this->db);
            exec($cmd, $this->_outputArray, $this->_returnCode);
            if ($this->_returnCode != 0) {
                throw new ErrorException(\Yii::t('base/console', 'Method error {placeholder}!', ['placeholder'=>'Erase migrations']));
            }
            
            $this->stdout(\Yii::t('base/console', 'Delete database {database}...' . PHP_EOL, ['database'=>$this->testDbName]));
            
            $cmd = sprintf("mysql --login-path=%s -e 'DROP DATABASE %s'", $this->loginPath, $this->testDbName);
            exec($cmd, $this->_outputArray, $this->_returnCode);
            if ($this->_returnCode != 0) {
                throw new ErrorException(\Yii::t('base/console', 'Method error {placeholder}!', ['placeholder'=>'DROP DATABASE $this->testDbName']));
            }
            
            $this->stdout(\Yii::t('base/console', 'Migrations erased, database {database} deleted successfully!' . PHP_EOL, ['database'=>$this->testDbName]));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->stderr(\Yii::t('base/console', 'Error delete database {database}!' . PHP_EOL, ['database'=>$this->testDbName]) . $t->getMessage() . '' . PHP_EOL, Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Экранирует аргументы
     */
    private function escapeArgs()
    {
        try {
            $this->db = escapeshellcmd(escapeshellarg($this->db));
            $this->loginPath = escapeshellcmd(escapeshellarg($this->loginPath));
            $this->testDbName = escapeshellcmd(escapeshellarg($this->testDbName));
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
