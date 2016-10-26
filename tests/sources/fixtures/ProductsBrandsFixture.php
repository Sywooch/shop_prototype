<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы products_brands
 */
class ProductsBrandsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу products_brands
     */
    public $modelClass = 'app\models\ProductsBrandsModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит products_brands
     */
    public $depends = [
        'app\tests\sources\fixtures\ProductsFixture',
        'app\tests\sources\fixtures\BrandsFixture',
    ];
}
