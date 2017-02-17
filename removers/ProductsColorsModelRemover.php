<?php

namespace app\removers;

use yii\base\{ErrorException,
    Model};
use app\removers\{AbstractBaseRemover,
    RemoverModelInterface};
use app\models\ProductsColorsModel;

/**
 * Удаляет данные из СУБД
 */
class ProductsColorsModelRemover extends AbstractBaseRemover implements RemoverModelInterface
{
   /**
     * @var ProductsColorsModel
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
            
            $result = ProductsColorsModel::deleteAll(['[[products_colors.id_product]]'=>$this->model->id_product]);
            
            return $result;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsColorsModel ProductsColorsModelRemover::models
     * @param $model ProductsColorsModel
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
