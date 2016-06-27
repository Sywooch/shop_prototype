<?php

namespace app\factories;

use app\factories\AbstractGetObjectsFactory;
use app\models\CommentsModel;

/**
 * Создает объекты на оснований данных БД
 */
class CommentsObjectsFactory extends AbstractGetObjectsFactory
{
    public function init()
    {
        parent::init();
        
        try {
            if (empty($this->model)) {
                $this->model = new CommentsModel(['scenario'=>CommentsModel::GET_FROM_DB]);
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
