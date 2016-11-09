<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы related_products
 */
class RelatedProductsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу related_products
     */
    public $modelClass = 'app\models\RelatedProductsModel';
     /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит related_products
     */
    public $depends = [
        'app\tests\sources\fixtures\ProductsFixture',
    ];
}
