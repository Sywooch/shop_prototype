<?php

namespace app\tests\sources\fixtures;

use app\tests\sources\fixtures\{AbstractFixture,
    EmailsFixture,
    ProductsFixture};
use app\models\CommentsModel;

/**
 * Фикстура таблицы comments
 */
class CommentsFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу comments
     */
    public $modelClass = CommentsModel::class;
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит comments
     */
    public $depends = [
        ProductsFixture::class,
        EmailsFixture::class
    ];
}
