<?php

namespace app\tests\queries;

use app\queries\SubcategoryQueryCreator;
use app\mappers\SubcategoryMapper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\queries\SubcategoryQueryCreator
 */
class SubcategoryQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $categoryModel = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DB]);
        $categoryModel->attributes = ['id'=>1];
        
        $subcategoryMapper = new SubcategoryMapper(['tableName'=>'subcategory', 'fields'=>['id', 'name'], 'model'=>$categoryModel]);
        $subcategoryMapper->visit(new SubcategoryQueryCreator());
        
        $query = 'SELECT [[subcategory.id]],[[subcategory.name]] FROM {{subcategory}} JOIN {{categories}} ON [[subcategory.id_categories]]=[[categories.id]] WHERE [[categories.id]]=:id';
        
        $this->assertEquals($query, $subcategoryMapper->query);
    }
}
