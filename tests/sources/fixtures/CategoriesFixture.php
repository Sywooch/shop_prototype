<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы categories
 */
class CategoriesFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу categories
     */
    public $modelClass = 'app\models\CategoriesModel';
}
