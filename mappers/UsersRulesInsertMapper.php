<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use yii\base\ErrorException;

/**
 * Добавляет записи в БД
*/
class UsersRulesInsertMapper extends AbstractInsertMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersRulesInsertQueryCreator';
    /**
     * @var string имя класса, который создает объекты из переданных данных
     */
    public $objectsClass = 'app\factories\UsersRulesFactory';
    
    /**
     * Формирует запрос к БД и выполняет его
     */
    protected function run()
    {
        try {
            $this->getUsersRulesDbArray();
            parent::run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает массивы для создания строк таблицы users_rules из данных модели UsersModel
     * @return array
     */
    private function getUsersRulesDbArray()
    {
        try {
            if (!isset($this->model)) {
                throw new ErrorException('Не передана модель!');
            }
            $result = array();
            $rulesForUser = $this->model->rulesFromForm;
            foreach ($rulesForUser as $rule) {
                $result[] = ['id_users'=>$this->model->id, 'id_rules'=>$rule];
            }
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
