<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы deliveries
 */
class DeliveriesFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу deliveries
     */
    public $modelClass = 'app\models\DeliveriesModel';
}
