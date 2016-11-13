<?php

namespace app\models;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;
use app\models\UsersModel;
use app\interfaces\SearchFilterInterface;
use app\helpers\SessionHelper;

class UsersFilter extends Model implements SearchFilterInterface
{
    use ExceptionsTrait;
    
    /**
     * Сценарий поиска данных в сессии
     */
    const SESSION_SEARCH = 'sessionSearch';
    
    /**
     * Принимает запрос на поиск данных, делегирует обработку в зависимости от сценария
     * @param string $scenario имя сценария поиска
     * @param mixed $data данные запроса ($_GET, $_POST и т.д)
     */
    public function search(string $scenario, $data=null)
    {
        try {
            switch ($scenario) {
                case self::SESSION_SEARCH:
                    return $this->sessionSearch($data);
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает строку с именем или э-почтой текущего пользовалеля, 
     * null, если данные не найдены
     * @return mixed
     */
    private function sessionSearch()
    {
        try {
            return SessionHelper::read(\Yii::$app->params['userKey']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
