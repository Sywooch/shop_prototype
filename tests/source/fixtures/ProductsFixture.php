<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

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
        'app\tests\source\fixtures\CategoriesFixture',
        'app\tests\source\fixtures\SubcategoryFixture',
    ];
}
