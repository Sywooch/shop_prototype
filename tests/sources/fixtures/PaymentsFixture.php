<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы payments
 */
class PaymentsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу payments
     */
    public $modelClass = 'app\models\PaymentsModel';
}
