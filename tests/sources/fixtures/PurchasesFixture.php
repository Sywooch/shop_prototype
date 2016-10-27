<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\AbstractFixture;

/**
 * Фикстура таблицы purchases
 */
class PurchasesFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу purchases
     */
    public $modelClass = 'app\models\PurchasesModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит purchases
     */
    public $depends = [
        'app\tests\sources\fixtures\ColorsFixture',
        'app\tests\sources\fixtures\SizesFixture',
        'app\tests\sources\fixtures\DeliveriesFixture',
        'app\tests\sources\fixtures\PaymentsFixture',
        'app\tests\sources\fixtures\UsersFixture',
        'app\tests\sources\fixtures\ProductsFixture',
    ];
}
