<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

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
