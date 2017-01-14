<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\PostcodesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает PostcodesModel из СУБД
 */
class PostcodePostcodeFinder extends AbstractBaseFinder
{
    /**
     * @var string postcode
     */
    public $postcode;
    /**
     * @var PostcodesModel
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
                if (empty($this->postcode)) {
                    throw new ErrorException($this->emptyError('postcode'));
                }
                
                $query = PostcodesModel::find();
                $query->select(['[[postcodes.id]]', '[[postcodes.postcode]]']);
                $query->where(['[[postcodes.postcode]]'=>$this->postcode]);
                
                $this->storage = $query->one();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
