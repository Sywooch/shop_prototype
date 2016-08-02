<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\RelatedProductsQueryCreator;

/**
 * Тестирует класс app\queries\RelatedProductsQueryCreator
 */
class RelatedProductsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'name', 'price', 'images'],
            'orderByField'=>'2',
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
        ]);
        
        $queryCreator = new RelatedProductsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.date]],[[products.name]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{related_products}} ON [[products.id]]=[[related_products.id_related_products]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[related_products.id_products]]=:id UNION SELECT [[products.id]],[[products.date]],[[products.name]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{related_products}} ON [[products.id]]=[[related_products.id_products]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[related_products.id_related_products]]=:id ORDER BY 2 DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
