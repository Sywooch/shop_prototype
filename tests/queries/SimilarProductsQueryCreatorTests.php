<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\SimilarProductsQueryCreator;

/**
 * Тестирует класс app\queries\SimilarProductsQueryCreator
 */
class SimilarProductsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'name', 'price', 'images'],
            'orderByField'=>'date',
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
            'model'=>new MockModel([
                'id'=>1, 
                'colors'=>[
                    new MockModel(['id'=>7]),
                    new MockModel(['id'=>1]),
                ],
                'sizes'=>[
                    new MockModel(['id'=>12]),
                    new MockModel(['id'=>7]),
                    new MockModel(['id'=>2]),
                ],
            ]),
        ]);
        
        $queryCreator = new SimilarProductsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.date]],[[products.name]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[products.id]]!=:id AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[colors.id]] IN (:colors0,:colors1) AND [[sizes.id]] IN (:sizes0,:sizes1,:sizes2) ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
