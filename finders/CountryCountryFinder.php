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
    public $country;
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
            if (empty($this->storage)) {
                if (empty($this->country)) {
                    throw new ErrorException($this->emptyError('country'));
                }
                
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
}
