<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;
use app\models\MailingsModel;

/**
 * Фикстура таблицы mailings
 */
class MailingsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу mailings
     */
    public $modelClass = MailingsModel::class;
}
