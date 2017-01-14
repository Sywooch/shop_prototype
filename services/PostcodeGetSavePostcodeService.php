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
     * @param array $request
     * @return PostcodesModel
     */
    public function handle($request): PostcodesModel
    {
        try {
            $this->postcode = $request['postcode'] ?? null;
            
            if (empty($this->postcode)) {
                throw new ErrorException($this->emptyError('postcode'));
            }
            
            if (empty($this->postcodesModel)) {
                $postcodesModel = $this->getCity();
                
                if ($postcodesModel === null) {
                    $rawCityModel = new PostcodesModel();
                    $rawCityModel->postcode = $this->postcode;
                    $saver = new ModelSaver([
                        'model'=>$rawCityModel
                    ]);
                    $saver->save();
                    
                    $postcodesModel = $this->getCity();
                    
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
    private function getCity()
    {
        try {
            $finder = \Yii::$app->registry->get(PostcodePostcodeFinder::class, ['postcode'=>$this->postcode]);
            $postcodesModel = $finder->find();
            
            return $postcodesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
