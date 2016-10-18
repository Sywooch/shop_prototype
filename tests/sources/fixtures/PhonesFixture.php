<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы phones
 */
class PhonesFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу phones
     */
    public $modelClass = 'app\models\PhonesModel';
}
