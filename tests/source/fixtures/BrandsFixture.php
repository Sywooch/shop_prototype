<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

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
