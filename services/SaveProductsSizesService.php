<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\ProductsSizesModel;
use app\savers\ProductsSizesArraySaver;
use app\removers\ProductsSizesModelRemover;

/**
 * Возвращает объект текущей валюты
 */
class SaveProductsSizesService extends AbstractBaseService
{
    /**
     * @var array
     */
    private $idSizes;
    /**
     * @var int
     */
    private $idProduct;
    
    /**
     * Сохраняет ProductsSizesModel
     * Первый запрос удаляет текущие связи, 
     * второй сохраняет новые данные
     * @return bool
     */
    public function get()
    {
        try {
            if (empty($this->idSizes)) {
                throw new ErrorException($this->emptyError('idSizes'));
            }
            if (empty($this->idProduct)) {
                throw new ErrorException($this->emptyError('idProduct'));
            }
            
            $removeProductsSizesModel = new ProductsSizesModel(['scenario'=>ProductsSizesModel::DELETE]);
            $removeProductsSizesModel->id_product = $this->idProduct;
            if ($removeProductsSizesModel->validate() === false) {
                throw new ErrorException($this->modelError($removeProductsSizesModel->errors));
            }
            $remover = new ProductsSizesModelRemover([
                'model'=>$removeProductsSizesModel
            ]);
            $remover->remove();
            
            $productsSizesModel = new ProductsSizesModel(['scenario'=>ProductsSizesModel::SAVE]);
            $productsSizesModelArray = [];
            foreach ($this->idSizes as $idSize) {
                $rawProductsSizesModel = clone $productsSizesModel;
                $rawProductsSizesModel->id_product = $this->idProduct;
                $rawProductsSizesModel->id_size = $idSize;
                if ($rawProductsSizesModel->validate() === false) {
                    throw new ErrorException($this->modelError($rawProductsSizesModel->errors));
                }
                $productsSizesModelArray[] = $rawProductsSizesModel;
            }
            $saver = new ProductsSizesArraySaver([
                'models'=>$productsSizesModelArray
            ]);
            $saver->save();
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SaveProductsSizesService::idSizes
     * @param array $idSizes
     */
    public function setIdSizes(array $idSizes)
    {
        try {
            $this->idSizes = $idSizes;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SaveProductsSizesService::idProduct
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
