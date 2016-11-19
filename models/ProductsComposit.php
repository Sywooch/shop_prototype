<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseComposit,
    ProductsCompositInterface};

/**
 * Реализует интерфейс доступа к данным о покупках в корзине
 */
class ProductsComposit extends AbstractBaseComposit implements ProductsCompositInterface
{
    /**
     * Коллекция сущностей
     */
    protected $items = [];
    
    /**
     * Добавляет сущность в коллекцию
     * @param mixed $model
     */
    public function add(ProductsModel $model)
    {
        try {
            $this->items[] = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает true, false в зависимости от того, пуст или нет ProductsComposit::items
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
