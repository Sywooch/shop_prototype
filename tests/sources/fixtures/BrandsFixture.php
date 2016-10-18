<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы colors
 */
class BrandsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу brands
     */
    public $modelClass = 'app\models\BrandsModel';
}
