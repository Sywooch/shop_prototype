<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы postcodes
 */
class PostcodesFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу postcodes
     */
    public $modelClass = 'app\models\PostcodesModel';
}
