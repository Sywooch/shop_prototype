<?php

namespace app\tests\queries;

use app\queries\RelatedProductsQueryCreator;
use app\mappers\RelatedProductsMapper;
use app\models\ProductsModel;
use app\tests\DbManager;

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
        $relatedProductsMapper = new RelatedProductsMapper([
            'tableName'=>'products',
            'fields'=>['id', 'name', 'price', 'images'],
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
            'orderByField'=>'date',
            'model'=>new ProductsModel(['id'=>1]),
        ]);
        $relatedProductsMapper->visit(new RelatedProductsQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{related_products}} ON [[products.id]]=[[related_products.id_related_products]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[related_products.id_products]]=:id UNION SELECT [[products.id]],[[products.name]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{related_products}} ON [[products.id]]=[[related_products.id_products]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[related_products.id_related_products]]=:id';
        
        $this->assertEquals($query, $relatedProductsMapper->query);
    }
}
