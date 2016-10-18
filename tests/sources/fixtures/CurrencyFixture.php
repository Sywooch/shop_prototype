<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы currency
 */
class CurrencyFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу currency
     */
    public $modelClass = 'app\models\CurrencyModel';
}
