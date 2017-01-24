<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;
use app\models\ColorsModel;

/**
 * Фикстура таблицы colors
 */
class ColorsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу colors
     */
    public $modelClass = ColorsModel::class;
}
