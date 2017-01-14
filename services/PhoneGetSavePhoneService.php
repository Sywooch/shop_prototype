<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\PhonesModel;
use app\finders\PhonePhoneFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект PhonesModel, 
 * при необходимости создает  и сохраняет новый
 */
class PhoneGetSavePhoneService extends AbstractBaseService
{
    /**
     * @var PhonesModel
     */
    private $phonesModel = null;
    /**
     * @var string
     */
    private $phone = null;
    
    /**
     * Возвращает PhonesModel по phone
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return PhonesModel
     */
    public function handle($request): PhonesModel
    {
        try {
            $this->phone = $request['phone'] ?? null;
            
            if (empty($this->phone)) {
                throw new ErrorException($this->emptyError('phone'));
            }
            
            if (empty($this->phonesModel)) {
                $phonesModel = $this->getPhone();
                
                if ($phonesModel === null) {
                    $rawPhonesModel = new PhonesModel();
                    $rawPhonesModel->phone = $this->phone;
                    $saver = new ModelSaver([
                        'model'=>$rawPhonesModel
                    ]);
                    $saver->save();
                    
                    $phonesModel = $this->getPhone();
                    
                    if ($phonesModel === null) {
                        throw new ErrorException($this->emptyError('phonesModel'));
                    }
                }
                
                $this->phonesModel = $phonesModel;
            }
            
            return $this->phonesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает PhonesModel из СУБД
     * @return mixed
     */
    private function getPhone()
    {
        try {
            $finder = \Yii::$app->registry->get(PhonePhoneFinder::class, ['phone'=>$this->phone]);
            $phonesModel = $finder->find();
            
            return $phonesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
