<?php

namespace app\mappers;

use yii\base\ErrorException;
use app\models\AbstractBaseModel;
use app\mappers\AbstractInsertMapper;

/**
 * Реализует вставку строк данных в БД
 */
abstract class AbstractUpdateMapper extends AbstractInsertMapper
{
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof AbstractBaseModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                if (empty($this->fields)) {
                    throw new ErrorException('Не определен массив полей, которые необходимо обновить!');
                }
                foreach ($this->fields as $field) {
                    $this->params[':' . $field] = $this->model->$field;
                }
                if (!array_key_exists(':id', $this->params)) {
                    if (empty($this->model->id)) {
                        throw new ErrorException('Отсутствует значение $this->model->id!');
                    }
                    $this->params[':id'] = $this->model->id;
                }
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
