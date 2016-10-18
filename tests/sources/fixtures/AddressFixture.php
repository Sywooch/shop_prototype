<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

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
