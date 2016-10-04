<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы emails_mailing_list
 */
class EmailsMailingListFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу emails_mailing_list
     */
    public $modelClass = 'app\models\EmailsMailingListModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит emails_mailing_list
     */
    public $depends = [
        'app\tests\source\fixtures\EmailsFixture',
        'app\tests\source\fixtures\MailingListFixture',
    ];
}
