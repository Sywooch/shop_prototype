<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractInsertQueryCreator;

abstract class AbstractUpdateQueryCreator extends AbstractInsertQueryCreator
{
    /**
     * Инициирует создание INSERT запроса
     * @return boolean
     */
    public function getInsertQuery()
    {
        try {
            if (!parent::getInsertQuery()) {
                throw new ErrorException('Ошибка при постороении запроса!');
            }
            $duplicate = $this->addOnDuplicateKeyUpdate();
            if (!is_string($duplicate)) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            $this->_mapperObject->query .= $duplicate;
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
