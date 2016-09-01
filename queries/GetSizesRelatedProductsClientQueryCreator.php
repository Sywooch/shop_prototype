<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class GetSizesRelatedProductsClientQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * Метод addCategoriesSubcategory() объявлен в трейте QueriesCreatorTrait
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $this->_mapperObject->query->distinct();
            
            $this->_mapperObject->query->innerJoin('products_sizes', '[[sizes.id]]=[[products_sizes.id_sizes]]');
            
            $this->_mapperObject->query->innerJoin('products', '[[products_sizes.id_products]]=[[products.id]]');
            
            if (!$this->addCategoriesSubcategory()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $this->_mapperObject->query->andWhere(['products.active'=>true]);
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
