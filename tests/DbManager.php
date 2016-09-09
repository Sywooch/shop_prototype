<?php

namespace app\tests;

use yii\base\Object;
use yii\base\ErrorException;

/**
 * Управляет созданием и удалением тестовой БД
 */
class DbManager extends Object
{
    /**
     * @var string логин к учетной записи с правами создания и удаления тестовой БД
     */
    public $login = 'root';
    /**
     * @var string пароль к учетной записи с правами создания и удаления тестовой БД
     */
    public $password = 'estet234';
    /**
     * @var string имя тестовой БД, которая будет создана
     */
    public $testDbName = 'shop_test';
    /**
     * @var string имя рабочей БД, струкутра которой будет скопирована
     */
    public $workDbName = 'shop';
    /**
     * @var string путь по которому будет сохранена и доступна скопированная структура рабочей БД
     */
    public $dbSchemePath;
    
    public function init()
    {
        parent::init();
        
        $this->dbSchemePath = __DIR__ . '/source/sql/shop.sql';
        
        $this->login = escapeshellcmd(escapeshellarg($this->login));
        $this->password = escapeshellcmd(escapeshellarg($this->password));
        $this->testDbName = escapeshellcmd(escapeshellarg($this->testDbName));
        $this->workDbName = escapeshellcmd(escapeshellarg($this->workDbName));
        $this->dbSchemePath = escapeshellcmd(escapeshellarg($this->dbSchemePath));
    }
    
    /**
     * Создает тестовую БД без заполнения данными
     */
    public function createDb()
    {
        try {
            $cmd = "mysql -u{$this->login} -p{$this->password} -e 'CREATE DATABASE IF NOT EXISTS {$this->testDbName}'";
            shell_exec($cmd);
            
            $cmd = "mysqldump -u{$this->login} -p{$this->password} --no-data {$this->workDbName} > {$this->dbSchemePath}";
            shell_exec($cmd);
            
            $cmd = "mysql -u{$this->login} -p{$this->password} {$this->testDbName} < {$this->dbSchemePath}";
            shell_exec($cmd);
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при создании тестовой БД!\n" . $e->getMessage());
        }
    }
    
    /**
     * Удаляет тестовую БД
     */
    public function deleteDb()
    {
        try {
            $cmd = "mysql -u{$this->login} -p{$this->password} -e 'DROP DATABASE {$this->testDbName}'";
            shell_exec($cmd);
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при удалении тестовой БД!\n" . $e->getMessage());
        }
        
        try {
            if (file_exists($this->dbSchemePath)) {
                unlink($this->dbSchemePath);
            }
        } catch (\Exception $e) {
            throw new ErrorException("Ошибка при удалении файла со структурой БД!\n" . $e->getMessage());
        }
    }
}
