<?php

namespace app\forms;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\forms\FormInterface;

/**
 * Базовый класс для моделей, представляющих данные форм
 */
abstract class AbstractBaseForm extends Model  implements FormInterface
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
            
            $model = new $name();
            $model->attributes = $data;
            
            return $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
