<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы products_sizes
 */
class ProductsSizesFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу products_sizes
     */
    public $modelClass = 'app\models\ProductsSizesModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит products_sizes
     */
    public $depends = [
        'app\tests\source\fixtures\ProductsFixture',
        'app\tests\source\fixtures\SizesFixture',
    ];
}
