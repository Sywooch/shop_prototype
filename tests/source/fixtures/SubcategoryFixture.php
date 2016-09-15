<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы subcategory
 */
class SubcategoryFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу categories
     */
    public $modelClass = 'app\models\SubcategoryModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит subcategory
     */
    public $depends = [
        'app\tests\source\fixtures\CategoriesFixture',
    ];
}
