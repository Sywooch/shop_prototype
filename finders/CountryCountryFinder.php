<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CountriesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает CountriesModel из СУБД
 */
class CountryCountryFinder extends AbstractBaseFinder
{
    /**
     * @var string country
     */
    private $country;
    /**
     * @var CountriesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->country)) {
                throw new ErrorException($this->emptyError('country'));
            }
            
            if (empty($this->storage)) {
                $query = CountriesModel::find();
                $query->select(['[[countries.id]]', '[[countries.country]]']);
                $query->where(['[[countries.country]]'=>$this->country]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает название страны свойству CountryCountryFinder::country
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
