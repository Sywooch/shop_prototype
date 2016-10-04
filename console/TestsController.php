<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
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
    public $_loginPath = 'root';
    /**
     * @var string имя тестовой БД, которая будет создана
     */
    private $_testDbName = 'shop_test';
    
    public function init()
    {
        try {
            parent::init();
            
            $this->escapeArgs();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function options($actionID)
    {
        try {
            return ['db'];
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает БД, применяет миграции
     * @return int
     */
    public function actionSet()
    {
        try {
            $this->stdout(\Yii::t('base/console', "Create database {database}...\n", ['database'=>$this->_testDbName]));
            
            $cmd = sprintf("mysql --login-path=%s -e 'CREATE DATABASE IF NOT EXISTS %s'",$this->_loginPath, $this->_testDbName);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Apply migrations...\n", ['database'=>$this->_testDbName]));
            
            $cmd = sprintf("/var/www/html/shop/yii migrate --db=%s --interactive=0", $this->db);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Database {database} created successfully, migrations applied!\n", ['database'=>$this->_testDbName]));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Error creating database {database}!\n", ['database'=>$this->_testDbName]), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Откатывает миграции, удаляет БД
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', "Erase migrations...\n", ['database'=>$this->_testDbName]));
            
            $cmd = sprintf("/var/www/html/shop/yii migrate/down --db=%s --interactive=0", $this->db);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Delete database {database}...\n", ['database'=>$this->_testDbName]));
            
            $cmd = sprintf("mysql --login-path=%s -e 'DROP DATABASE %s'", $this->_loginPath, $this->_testDbName);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Migrations erased, database {database} deleted successfully!\n", ['database'=>$this->_testDbName]));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Error delete database {database}!\n", ['database'=>$this->_testDbName]), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Экранирует аргументы
     */
    private function escapeArgs()
    {
        try {
            $this->_testDbName = escapeshellcmd(escapeshellarg($this->_testDbName));
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
