<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
use app\traits\ExceptionsTrait;

class TestsController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * @var string ID компонента базы данных, подключенного в файле конфигурации
     */
    public $db = 'dbTest';
    /**
     * @var string логин к учетной записи с правами создания и удаления тестовой БД
     */
    public $username = null;
    /**
     * @var string пароль к учетной записи с правами создания и удаления тестовой БД
     */
    public $password = null;
    /**
     * @var string имя тестовой БД, которая будет создана
     */
    public $testDbName = null;
    
    public function init()
    {
        try {
            parent::init();
            
            $db = $this->db;
            $currentDb = \Yii::$app->$db;
            
            $this->username = $this->username ?? $currentDb->username;
            $this->password = $this->password ?? $currentDb->password;
            preg_match('/.*dbname=([a-z1-9_]+)$/', $currentDb->dsn, $matches);
            $this->testDbName = $this->testDbName ?? $matches[1];
            
            $this->escapeArgs();
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает БД, применяет миграции
     */
    public function actionSet()
    {
        try {
            $this->stdout(\Yii::t('base/console', "Create database {database}...\n", ['database'=>$this->testDbName]));
            
            $cmd = sprintf("mysql -u%s -p%s -e 'CREATE DATABASE IF NOT EXISTS %s'", $this->username, $this->password, $this->testDbName);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Apply migrations...\n", ['database'=>$this->testDbName]));
            
            $cmd = sprintf("/var/www/html/shop/yii migrate --db=%s --interactive=0", $this->db);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Database {database} created successfully, migrations applied!\n", ['database'=>$this->testDbName]));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Error creating database {database}!\n", ['database'=>$this->testDbName]), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Откатывает миграции, удаляет БД
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', "Erase migrations...\n", ['database'=>$this->testDbName]));
            
            $cmd = sprintf("/var/www/html/shop/yii migrate/down --db=%s --interactive=0", $this->db);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Delete database {database}...\n", ['database'=>$this->testDbName]));
            
            $cmd = sprintf("mysql -u%s -p%s -e 'DROP DATABASE %s'", $this->username, $this->password, $this->testDbName);
            exec($cmd);
            
            $this->stdout(\Yii::t('base/console', "Migrations erased, database {database} deleted successfully!\n", ['database'=>$this->testDbName]));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Error delete database {database}!\n", ['database'=>$this->testDbName]), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Экранирует аргументы
     */
    private function escapeArgs()
    {
        try {
            $this->username = escapeshellcmd(escapeshellarg($this->username));
            $this->password = escapeshellcmd(escapeshellarg($this->password));
            $this->testDbName = escapeshellcmd(escapeshellarg($this->testDbName));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
