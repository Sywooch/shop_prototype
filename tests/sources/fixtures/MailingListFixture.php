<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы mailing_list
 */
class MailingListFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу mailing_list
     */
    public $modelClass = 'app\models\MailingListModel';
}
