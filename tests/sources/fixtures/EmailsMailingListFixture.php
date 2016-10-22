<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

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
        'app\tests\sources\fixtures\MailingListFixture',
        'app\tests\sources\fixtures\EmailsFixture',
    ];
}
