<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class ProductsSphynxQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (empty(\Yii::$app->params['sphynxKey'])) {
                throw new ErrorException('Не поределен sphynxKey!');
            }
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не поределен searchKey!');
            }
            
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $this->_mapperObject->query->where('MATCH(:' . \Yii::$app->params['sphynxKey'] . ')', [':' . \Yii::$app->params['sphynxKey']=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
