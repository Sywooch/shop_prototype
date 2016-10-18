<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

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
        'app\tests\sources\fixtures\CategoriesFixture',
    ];
}
