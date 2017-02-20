<?php

namespace app\removers;

use yii\base\{ErrorException,
    Model};
use app\removers\{AbstractBaseRemover,
    RemoverModelInterface};
use app\models\ProductsModel;

/**
 * Удаляет данные из СУБД
 */
class ProductsModelRemover extends AbstractBaseRemover implements RemoverModelInterface
{
   /**
     * @var ProductsModel
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
            
            $result = ProductsModel::deleteAll(['[[products.id]]'=>$this->model->id]);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsModel ProductsModelRemover::models
     * @param $model ProductsModel
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
