<?php

namespace app\queries;

use yii\base\ErrorException;
use app\queries\AbstractDeleteQueryCreator;

/**
 * Конструирует запрос к БД
 */
class BrandsDeleteQueryCreator extends AbstractDeleteQueryCreator
{
    /**
     * @var string имя поля в БД для условия WHERE
     */
    public $fieldWhere = 'id';
}
