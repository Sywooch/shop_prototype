<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\{AbstractBaseRepository,
    GetGroupRepositoryInterface,
    GetOneRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\{ProductsCompositInterface,
    ProductsModel};

class ProductsRepository extends AbstractBaseRepository implements GetOneRepositoryInterface, GetGroupRepositoryInterface
{
    /**
     * @var object ProductsCompositInterface
     */
    private $items;
    /**
     * @var object ProductsModel
     */
    private $item;
    
    /**
     * Возвращает ProductsModel
     * @return ProductsModel или null
     */
    public function getOne($data=null)
    {
        try {
            if (empty($this->item)) {
                $query = ProductsModel::find();
                $query = $this->addCriteria($query);
                $data = $query->one();
                if ($data !== null) {
                    $this->item = $data;
                }
            }
            
            return !empty($this->item) ? $this->item : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает ProductsCompositInterface, содержащий коллекцию ProductsModel
     * @param object $model ProductsModel для поиска данных в хранилище
     * @return ProductsCompositInterface или null
     */
    public function getGroup($model)
    {
        try {
            if (!$model instanceof ProductsModel) {
                throw new ErrorException(ExceptionsTrait::emptyError('model'));
            }
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $query = ProductsModel::find();
                $query = $this->addCriteria($query);
                $data = $query->all();
                if (!empty($data)) {
                    foreach ($data as $object) {
                        $this->items->add($object);
                    }
                }
            }
            
            return !empty($this->items) ? $this->items : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает ProductsCompositInterface свойству RelatedProductsRepository::items
     * @param object $composit ProductsCompositInterface
     */
    public function setItems(ProductsCompositInterface $composit)
    {
        try {
            $this->items = $composit;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
