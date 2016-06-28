<?php

namespace app\mappers;

use app\mappers\AbstractInsertMapper;
use yii\base\ErrorException;

/**
 * Реализует вставку строк данных в БД
 */
abstract class AbstractUpdateMapper extends AbstractInsertMapper
{
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model)) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty($this->fields)) {
                    throw new ErrorException('Не определен массив полей, которые необходимо обновить!');
                }
                foreach ($this->fields as $field) {
                    $this->params[':' . $field] = $this->model->$field;
                }
                $this->params[':id'] = $this->model->id;
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
