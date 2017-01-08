<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\NamesModel;
use app\finders\NameNameFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект NamesModel
 */
class NameGetSaveNameService extends AbstractBaseService
{
    /**
     * @var NamesModel
     */
    private $namesModel = null;
    /**
     * @var string
     */
    private $name = null;
    
    /**
     * Возвращает NamesModel по name
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return NamesModel
     */
    public function handle($request): NamesModel
    {
        try {
            if (empty($request['name'])) {
                throw new ErrorException($this->emptyError('request'));
            }
            
            if (empty($this->namesModel)) {
                $this->name = $request['name'];
                
                $namesModel = $this->getName();
                
                if ($namesModel === null) {
                    $rawNamesModel = new NamesModel();
                    $rawNamesModel->name = $this->name;
                    $saver = new ModelSaver([
                        'model'=>$rawNamesModel
                    ]);
                    $saver->save();
                    
                    $namesModel = $this->getName();
                    
                    if ($namesModel === null) {
                        throw new ErrorException($this->emptyError('namesModel'));
                    }
                }
                
                $this->namesModel = $namesModel;
            }
            
            return $this->namesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает NamesModel из СУБД
     * @return mixed
     */
    private function getName()
    {
        try {
            $finder = \Yii::$app->registry->get(NameNameFinder::class, ['name'=>$this->name]);
            $namesModel = $finder->find();
            
            return $namesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
