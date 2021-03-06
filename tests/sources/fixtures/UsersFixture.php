<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы users
 */
class UsersFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу users
     */
    public $modelClass = 'app\models\UsersModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит users
     */
    public $depends = [
        'app\tests\sources\fixtures\EmailsFixture',
        'app\tests\sources\fixtures\NamesFixture',
        'app\tests\sources\fixtures\SurnamesFixture',
        'app\tests\sources\fixtures\PhonesFixture',
        'app\tests\sources\fixtures\AddressFixture',
        'app\tests\sources\fixtures\CitiesFixture',
        'app\tests\sources\fixtures\CountriesFixture',
        'app\tests\sources\fixtures\PostcodesFixture',
    ];
}
