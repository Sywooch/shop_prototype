<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\RelatedProductsModel;
use app\savers\RelatedProductsArraySaver;
use app\removers\RelatedProductsModelRemover;

/**
 * Возвращает объект текущей валюты
 */
class SaveRelatedProductsService extends AbstractBaseService
{
    /**
     * @var array
     */
    private $idRelatedProducts;
    /**
     * @var int
     */
    private $idProduct;
    
    /**
     * Сохраняет RelatedProductsModel
     * Первый запрос удаляет текущие связи, 
     * второй сохраняет новые данные
     * @return bool
     */
    public function get()
    {
        try {
            if (empty($this->idProduct)) {
                throw new ErrorException($this->emptyError('idProduct'));
            }
            
            $removeRelatedProductsModel = new RelatedProductsModel(['scenario'=>RelatedProductsModel::DELETE]);
            $removeRelatedProductsModel->id_product = $this->idProduct;
            if ($removeRelatedProductsModel->validate() === false) {
                throw new ErrorException($this->modelError($removeRelatedProductsModel->errors));
            }
            $remover = new RelatedProductsModelRemover([
                'model'=>$removeRelatedProductsModel
            ]);
            $remover->remove();
            
            if (!empty($this->idRelatedProducts)) {
                $relatedProductsModel = new RelatedProductsModel(['scenario'=>RelatedProductsModel::SAVE]);
                $relatedProductsModelArray = [];
                foreach ($this->idRelatedProducts as $idRelatedProduct) {
                    $rawRelatedProductsModel = clone $relatedProductsModel;
                    $rawRelatedProductsModel->id_product = $this->idProduct;
                    $rawRelatedProductsModel->id_related_product = $idRelatedProduct;
                    if ($rawRelatedProductsModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawRelatedProductsModel->errors));
                    }
                    $relatedProductsModelArray[] = $rawRelatedProductsModel;
                }
                $saver = new RelatedProductsArraySaver([
                    'models'=>$relatedProductsModelArray
                ]);
                $saver->save();
            }
            
            return true;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SaveRelatedProductsService::idRelatedProducts
     * @param array $idRelatedProducts
     */
    public function setIdRelatedProducts(array $idRelatedProducts)
    {
        try {
            $this->idRelatedProducts = $idRelatedProducts;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение SaveRelatedProductsService::idProduct
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
