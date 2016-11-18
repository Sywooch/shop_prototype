<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseComposit,
    CurrencyCompositInterface,
    CurrencyModel};

/**
 * Реализует интерфейс доступа к данным о покупках в корзине
 */
class CurrencyComposit extends AbstractBaseComposit implements CurrencyCompositInterface
{
    /**
     * Коллекция сущностей
     */
    protected $items = [];
    
    /**
     * Добавляет сущность в коллекцию
     * @param object $model CurrencyModel
     */
    public function add(CurrencyModel $model)
    {
        try {
            $this->items[] = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает true, false в зависимости от того, пуст или нет CurrencyComposit::items
     */
    public function isEmpty()
    {
        try {
            return empty($this->items) ? true : false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
