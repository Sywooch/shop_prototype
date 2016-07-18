<?php

namespace app\exceptions;

use yii\base\ErrorException;

/**
 * Исключение, выбрасываемое при возврате пустого массива продуктов из БД
 */
class EmptyListException extends ErrorException
{
    
}
