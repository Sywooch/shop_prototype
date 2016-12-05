<?php

namespace app\forms;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс для моделей, представляющих данные форм
 */
abstract class AbstractBaseForm extends Model
{
    use ExceptionsTrait;
    
    /**
     * Возвращает объект модели, представляющий таблицу СУБД
     * @return Model
     */
    public function getModel(string $name): Model
    {
        try {
            $data = array_filter($this->toArray());
            
            if (empty($data)) {
                throw new ErrorException($this->emptyError('toArray'));
            }
            
            return new $name($data);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
