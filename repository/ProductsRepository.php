<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\{AbstractBaseRepository,
    GetOneRepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

class ProductsRepository extends AbstractBaseRepository implements GetOneRepositoryInterface
{
    /**
     * @var object ProductsModel
     */
    private $item;
    
    /**
     * Возвращает ProductsModel
     * @param string $seocode ключ для поиска данных в хранилище
     * @return ProductsModel или null
     */
    public function getOne($seocode)
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
}
