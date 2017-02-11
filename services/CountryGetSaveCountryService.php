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
     * @return CountriesModel
     */
    public function get(): CountriesModel
    {
        try {
            if (empty($this->country)) {
                throw new ErrorException($this->emptyError('country'));
            }
            
            if (empty($this->countriesModel)) {
                $countriesModel = $this->getCountry();
                
                if ($countriesModel === null) {
                    $rawCountriesModel = new CountriesModel(['scenario'=>CountriesModel::SAVE]);
                    $rawCountriesModel->country = $this->country;
                    if ($rawCountriesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawCountriesModel->errors));
                    }
                    
                    $saver = new ModelSaver([
                        'model'=>$rawCountriesModel
                    ]);
                    $saver->save();
                    
                    $countriesModel = $this->getCountry();
                    
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
    private function getCountry()
    {
        try {
            $finder = \Yii::$app->registry->get(CountryCountryFinder::class, [
                'country'=>$this->country
            ]);
            $countriesModel = $finder->find();
            
            return $countriesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение CountryGetSaveCountryService::country
     * @param string $country
     */
    public function setCountry(string $country)
    {
        try {
            $this->country = $country;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
