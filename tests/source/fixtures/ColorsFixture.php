<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы colors
 */
class ColorsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу colors
     */
    public $modelClass = 'app\models\ColorsModel';
}
