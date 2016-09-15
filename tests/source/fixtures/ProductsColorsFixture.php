<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы products_colors
 */
class ProductsColorsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу products_colors
     */
    public $modelClass = 'app\models\ProductsColorsModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит products_colors
     */
    public $depends = [
        'app\tests\source\fixtures\ProductsFixture',
        'app\tests\source\fixtures\ColorsFixture',
    ];
}
