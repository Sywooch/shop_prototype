<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\AddressModel;
use app\finders\AddressAddressFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект AddressModel, 
 * при необходимости создает  и сохраняет новый
 */
class AddressGetSaveAddressService extends AbstractBaseService
{
    /**
     * @var AddressModel
     */
    private $addressModel = null;
    /**
     * @var string
     */
    private $address = null;
    
    /**
     * Возвращает AddressModel по address
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @return AddressModel
     */
    public function get(): AddressModel
    {
        try {
            if (empty($this->address)) {
                throw new ErrorException($this->emptyError('address'));
            }
            
            if (empty($this->addressModel)) {
                $addressModel = $this->getAddress();
                
                if ($addressModel === null) {
                    $rawAddressModel = new AddressModel(['scenario'=>AddressModel::SAVE]);
                    $rawAddressModel->address = $this->address;
                    if ($rawAddressModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawAddressModel->errors));
                    }
                    
                    $saver = new ModelSaver([
                        'model'=>$rawAddressModel
                    ]);
                    $saver->save();
                    
                    $addressModel = $this->getAddress();
                    
                    if ($addressModel === null) {
                        throw new ErrorException($this->emptyError('addressModel'));
                    }
                }
                
                $this->addressModel = $addressModel;
            }
            
            return $this->addressModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает AddressModel из СУБД
     * @return mixed
     */
    private function getAddress()
    {
        try {
            $finder = \Yii::$app->registry->get(AddressAddressFinder::class, ['address'=>$this->address]);
            $addressModel = $finder->find();
            
            return $addressModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AddressGetSaveAddressService::address
     * @param string $address
     */
    public function setAddress(string $address)
    {
        try {
            $this->address = $address;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
