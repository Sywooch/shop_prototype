<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы products
 */
class ProductsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу categories
     */
    public $modelClass = 'app\models\ProductsModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит products
     */
    public $depends = [
        'app\tests\sources\fixtures\CategoriesFixture',
        'app\tests\sources\fixtures\SubcategoryFixture',
        'app\tests\sources\fixtures\ColorsFixture',
        'app\tests\sources\fixtures\BrandsFixture',
    ];
}
