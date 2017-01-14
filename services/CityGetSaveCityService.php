<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\CitiesModel;
use app\finders\CityCityFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект CitiesModel, 
 * при необходимости создает  и сохраняет новый
 */
class CityGetSaveCityService extends AbstractBaseService
{
    /**
     * @var CitiesModel
     */
    private $citiesModel = null;
    /**
     * @var string
     */
    private $city = null;
    
    /**
     * Возвращает CitiesModel по city
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return CitiesModel
     */
    public function handle($request): CitiesModel
    {
        try {
            $this->city = $request['city'] ?? null;
            
            if (empty($this->city)) {
                throw new ErrorException($this->emptyError('city'));
            }
            
            if (empty($this->citiesModel)) {
                $citiesModel = $this->getCity();
                
                if ($citiesModel === null) {
                    $rawCityModel = new CitiesModel();
                    $rawCityModel->city = $this->city;
                    $saver = new ModelSaver([
                        'model'=>$rawCityModel
                    ]);
                    $saver->save();
                    
                    $citiesModel = $this->getCity();
                    
                    if ($citiesModel === null) {
                        throw new ErrorException($this->emptyError('citiesModel'));
                    }
                }
                
                $this->citiesModel = $citiesModel;
            }
            
            return $this->citiesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает CitiesModel из СУБД
     * @return mixed
     */
    private function getCity()
    {
        try {
            $finder = \Yii::$app->registry->get(CityCityFinder::class, ['city'=>$this->city]);
            $citiesModel = $finder->find();
            
            return $citiesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
