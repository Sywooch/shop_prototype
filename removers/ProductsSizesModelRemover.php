<?php

namespace app\removers;

use yii\base\{ErrorException,
    Model};
use app\removers\{AbstractBaseRemover,
    RemoverModelInterface};
use app\models\ProductsSizesModel;

/**
 * Удаляет данные из СУБД
 */
class ProductsSizesModelRemover extends AbstractBaseRemover implements RemoverModelInterface
{
   /**
     * @var ProductsSizesModel
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
            
            $result = ProductsSizesModel::deleteAll(['[[products_sizes.id_product]]'=>$this->model->id_product]);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsSizesModel ProductsSizesModelRemover::models
     * @param $model ProductsSizesModel
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
