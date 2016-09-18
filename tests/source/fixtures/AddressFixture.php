<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы address
 */
class AddressFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу address
     */
    public $modelClass = 'app\models\AddressModel';
}
