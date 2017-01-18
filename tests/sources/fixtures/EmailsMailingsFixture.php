<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\{AbstractFixture,
    EmailsFixture,
    MailingsFixture};
use app\models\EmailsMailingsModel;

/**
 * Фикстура таблицы emails_mailings
 */
class EmailsMailingsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу emails_mailings
     */
    public $modelClass = EmailsMailingsModel::class;
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит emails_mailings
     */
    public $depends = [
        MailingsFixture::class,
        EmailsFixture::class,
    ];
}
