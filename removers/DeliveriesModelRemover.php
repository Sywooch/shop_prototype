<?php

namespace app\removers;

use yii\base\{ErrorException,
    Model};
use app\removers\{AbstractBaseRemover,
    RemoverModelInterface};
use app\models\DeliveriesModel;

/**
 * Удаляет данные из СУБД
 */
class DeliveriesModelRemover extends AbstractBaseRemover implements RemoverModelInterface
{
   /**
     * @var Model
     */
    private $model;
    
    /**
     * Удаляет данные
     * @return int
     */
    public function remove()
    {
        try {
            if (empty($this->model)) {
                throw new ErrorException($this->emptyError('model'));
            }
            
            $result = DeliveriesModel::deleteAll(['[[deliveries.id]]'=>$this->model->id]);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение DeliveriesModelRemover::models
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        try {
            $this->model = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
