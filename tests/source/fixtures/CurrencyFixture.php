<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

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
