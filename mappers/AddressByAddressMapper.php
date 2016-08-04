<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;
use app\models\AddressModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class AddressByAddressMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\AddressByAddressQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\AddressObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof AddressModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                $this->params[':address'] = $this->model->address ? $this->model->address : '';
                $this->params[':city'] = $this->model->city ? $this->model->city : '';
                $this->params[':country'] = $this->model->country ? $this->model->country : '';
                $this->params[':postcode'] = $this->model->postcode ? $this->model->postcode : '';
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
