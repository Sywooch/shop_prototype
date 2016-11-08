<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы emails_mailings
 */
class EmailsMailingsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу emails_mailings
     */
    public $modelClass = 'app\models\EmailsMailingsModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит emails_mailings
     */
    public $depends = [
        'app\tests\sources\fixtures\MailingsFixture',
        'app\tests\sources\fixtures\EmailsFixture',
    ];
}
