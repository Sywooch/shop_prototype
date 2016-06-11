<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;
use app\exceptions\LostDataUserException;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
abstract class AbstractGetOneMapper extends AbstractBaseMapper
{
    /**
     * @var string имя ключа и переменной для $command->bindValue
     */
    public $paramBindKey;
    /**
     * @var string значение ключа для $command->bindValue
     */
    public $paramBindKeyValue;
    
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getOne()
    {
        try {
            $this->run();
        } catch (LostDataUserException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsOne;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':' . $this->paramBindKey, $this->paramBindKeyValue);
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
