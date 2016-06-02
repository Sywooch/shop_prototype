<?php

namespace app\tests\queries;

use app\queries\SimilarProductsQueryCreator;
use app\mappers\SimilarProductsMapper;
use app\models\ProductsModel;
use app\tests\DbManager;

/**
 * Тестирует класс app\queries\SimilarProductsQueryCreator
 */
class SimilarProductsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $similarProductsMapper = new SimilarProductsMapper([
            'tableName'=>'products',
            'fields'=>['id', 'name', 'price', 'images'],
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
            'orderByField'=>'date',
            'model'=>new ProductsModel(['id'=>4]),
        ]);
        $similarProductsMapper->visit(new SimilarProductsQueryCreator());
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.name]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[products.id]]!=:id AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[colors.id]] IN (:colors0,:colors1) AND [[sizes.id]] IN (:sizes0)';
        
        $this->assertEquals($query, $similarProductsMapper->query);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
