<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\CountriesModel;
use app\finders\CountryCountryFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект CountriesModel, 
 * при необходимости создает и сохраняет новый
 */
class CountryGetSaveCountryService extends AbstractBaseService
{
    /**
     * @var CountriesModel
     */
    private $countriesModel = null;
    /**
     * @var string
     */
    private $country = null;
    
    /**
     * Возвращает CountriesModel по country
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return CountriesModel
     */
    public function handle($request): CountriesModel
    {
        try {
            $this->country = $request['country'] ?? null;
            
            if (empty($this->country)) {
                throw new ErrorException($this->emptyError('country'));
            }
            
            if (empty($this->countriesModel)) {
                $countriesModel = $this->getCity();
                
                if ($countriesModel === null) {
                    $rawCityModel = new CountriesModel();
                    $rawCityModel->country = $this->country;
                    $saver = new ModelSaver([
                        'model'=>$rawCityModel
                    ]);
                    $saver->save();
                    
                    $countriesModel = $this->getCity();
                    
                    if ($countriesModel === null) {
                        throw new ErrorException($this->emptyError('countriesModel'));
                    }
                }
                
                $this->countriesModel = $countriesModel;
            }
            
            return $this->countriesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает CountriesModel из СУБД
     * @return mixed
     */
    private function getCity()
    {
        try {
            $finder = \Yii::$app->registry->get(CountryCountryFinder::class, ['country'=>$this->country]);
            $countriesModel = $finder->find();
            
            return $countriesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
