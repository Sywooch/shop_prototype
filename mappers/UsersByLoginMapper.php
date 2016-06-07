<?php

namespace app\mappers;

use app\mappers\AbstractGetOneMapper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class UsersByLoginMapper extends AbstractGetOneMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersByLoginQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\UsersOneObjectsFactory';
    /**
     * @var object объект, для которого необходимо получить ID
     */
    public $model;
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            if (!isset($this->model) || !isset($this->model->login)) {
                throw new ErrorException('Не передана модель!');
            }
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':login', $this->model->login);
            $result = $command->queryOne();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
