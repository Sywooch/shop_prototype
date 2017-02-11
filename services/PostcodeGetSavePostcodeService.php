<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\PostcodesModel;
use app\finders\PostcodePostcodeFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект PostcodesModel, 
 * при необходимости создает и сохраняет новый
 */
class PostcodeGetSavePostcodeService extends AbstractBaseService
{
    /**
     * @var PostcodesModel
     */
    private $postcodesModel = null;
    /**
     * @var string
     */
    private $postcode = null;
    
    /**
     * Возвращает PostcodesModel по postcode
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @return PostcodesModel
     */
    public function get(): PostcodesModel
    {
        try {
            if (empty($this->postcode)) {
                throw new ErrorException($this->emptyError('postcode'));
            }
            
            if (empty($this->postcodesModel)) {
                $postcodesModel = $this->getPostcode();
                
                if ($postcodesModel === null) {
                    $rawPostcodesModel = new PostcodesModel(['scenario'=>PostcodesModel::SAVE]);
                    $rawPostcodesModel->postcode = $this->postcode;
                    if ($rawPostcodesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawPostcodesModel->errors));
                    }
                    
                    $saver = new ModelSaver([
                        'model'=>$rawPostcodesModel
                    ]);
                    $saver->save();
                    
                    $postcodesModel = $this->getPostcode();
                    
                    if ($postcodesModel === null) {
                        throw new ErrorException($this->emptyError('postcodesModel'));
                    }
                }
                
                $this->postcodesModel = $postcodesModel;
            }
            
            return $this->postcodesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает PostcodesModel из СУБД
     * @return mixed
     */
    private function getPostcode()
    {
        try {
            $finder = \Yii::$app->registry->get(PostcodePostcodeFinder::class, [
                'postcode'=>$this->postcode
            ]);
            $postcodesModel = $finder->find();
            
            return $postcodesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение PostcodeGetSavePostcodeService::postcode
     * @param string $postcode
     */
    public function setPostcode(string $postcode)
    {
        try {
            $this->postcode = $postcode;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
