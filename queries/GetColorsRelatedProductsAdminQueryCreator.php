<?php

namespace app\queries;

use app\queries\AbstractSeletcQueryCreator;

/**
 * Конструирует запрос к БД для получения списка строк
 */
class GetColorsRelatedProductsAdminQueryCreator extends AbstractSeletcQueryCreator
{
    /**
     * Инициирует создание SELECT запроса
     * Метод addCategoriesSubcategoryAdmin() объявлен в трейте QueriesCreatorTrait
     * @return boolean
     */
    public function getSelectQuery()
    {
        try {
            if (!parent::getSelectQuery()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            $this->_mapperObject->query->distinct();
            
            $this->_mapperObject->query->innerJoin('products_colors', '[[colors.id]]=[[products_colors.id_colors]]');
            
            if (!empty(\Yii::$app->filters->categories)) {
                $this->_mapperObject->query->innerJoin('products', '[[products_colors.id_products]]=[[products.id]]');
            } 
            
            if (!$this->addCategoriesSubcategoryAdmin()) {
                throw new ErrorException('Ошибка при построении запроса!');
            }
            
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
