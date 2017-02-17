<?php

namespace app\services;

use yii\base\{ErrorException,
    Model};
use yii\helpers\ArrayHelper;
use app\services\AbstractBaseService;
use app\models\ProductsColorsModel;
use app\savers\ProductsColorsArraySaver;
use app\remover\ProductsColorsModelRemover;

/**
 * Возвращает объект текущей валюты
 */
class SaveProductsColorsService extends AbstractBaseService
{
    /**
     * @var array
     */
    private $idColors;
    /**
     * @var int
     */
    private $idProduct;
    
    /**
     * Сохраняет ProductsColorsModel
     * Первый запрос удаляет текущие связи, 
     * второй сохраняет новые данные
     * @return bool
     */
    public function get()
    {
        try {
            if (empty($this->idColors)) {
                throw new ErrorException($this->emptyError('idColors'));
            }
            if (empty($this->idProduct)) {
                throw new ErrorException($this->emptyError('idProduct'));
            }
            
            $removeProductsColorsModel = new ProductsColorsModel(['scenario'=>ProductsColorsModel::DELETE]);
            $removeProductsColorsModel->id_product = $this->idProduct;
            if ($removeProductsColorsModel->validate() === false) {
                throw new ErrorException($this->modelError($removeProductsColorsModel->errors));
            }
            $remover = new ProductsColorsModelRemover([
                'model'=>$removeProductsColorsModel
            ]);
            $remover->remove();
            
            $productsColorsModel = new ProductsColorsModel(['scenario'=>ProductsColorsModel::SAVE]);
            $productsColorsModelArray = [];
            foreach ($this->idColors as $idColor) {
                $rawProductsColorsModel = clone $productsColorsModel;
                $rawProductsColorsModel->id_product = $this->idProduct;
                $rawProductsColorsModel->id_color = $idColor;
                if ($rawProductsColorsModel->validate() === false) {
                    throw new ErrorException($this->modelError($rawProductsColorsModel->errors));
                }
                $productsColorsModelArray[] = $rawProductsColorsModel;
            }
            $saver = new ProductsColorsArraySaver([
                'models'=>$productsColorsModelArray
            ]);
            $saver->save();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SaveProductsColorsService::idColors
     * @param array $idColors
     */
    public function setIdColors(array $idColors)
    {
        try {
            $this->idColors = $idColors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SaveProductsColorsService::idProduct
     * @param int $idProduct
     */
    public function setIdProduct(int $idProduct)
    {
        try {
            $this->idProduct = $idProduct;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
