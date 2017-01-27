<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\CitiesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает CitiesModel из СУБД
 */
class CityCityFinder extends AbstractBaseFinder
{
    /**
     * @var string city
     */
    private $city;
    /**
     * @var CitiesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return mixed
     */
    public function find()
    {
        try {
            if (empty($this->city)) {
                throw new ErrorException($this->emptyError('city'));
            }
            
            if (empty($this->storage)) {
                $query = CitiesModel::find();
                $query->select(['[[cities.id]]', '[[cities.city]]']);
                $query->where(['[[cities.city]]'=>$this->city]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает название города свойству CityCityFinder::city
     * @param string $city
     */
    public function setСity(string $city)
    {
        try {
            $this->city = $city;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
