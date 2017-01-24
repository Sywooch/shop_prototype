<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;
use app\models\VisitorsCounterModel;

/**
 * Фикстура таблицы visitors_counter
 */
class VisitorsCounterFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу visitors_counter
     */
    public $modelClass = VisitorsCounterModel::class;
}
