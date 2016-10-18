<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

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
        'app\tests\sources\fixtures\ProductsFixture',
        'app\tests\sources\fixtures\ColorsFixture',
    ];
}
