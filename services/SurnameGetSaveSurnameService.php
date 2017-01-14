<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\models\SurnamesModel;
use app\finders\SurnameSurnameFinder;
use app\savers\ModelSaver;

/**
 * Возвращает объект SurnamesModel
 */
class SurnameGetSaveSurnameService extends AbstractBaseService
{
    /**
     * @var SurnamesModel
     */
    private $surnamesModel = null;
    /**
     * @var string
     */
    private $surname = null;
    
    /**
     * Возвращает SurnamesModel по surname
     * Первый запрос отправляет в СУБД, 
     * если данных нет, конструирует и сохраняет новый объект
     * @param array $request
     * @return SurnamesModel
     */
    public function handle($request): SurnamesModel
    {
        try {
            $this->surname = $request['surname'] ?? null;
            
            if (empty($this->surname)) {
                throw new ErrorException($this->emptyError('surname'));
            }
            
            if (empty($this->surnamesModel)) {
                $surnamesModel = $this->getSurname();
                
                if ($surnamesModel === null) {
                    $rawSurnamesModel = new SurnamesModel();
                    $rawSurnamesModel->surname = $this->surname;
                    $saver = new ModelSaver([
                        'model'=>$rawSurnamesModel
                    ]);
                    $saver->save();
                    
                    $surnamesModel = $this->getSurname();
                    
                    if ($surnamesModel === null) {
                        throw new ErrorException($this->emptyError('surnamesModel'));
                    }
                }
                
                $this->surnamesModel = $surnamesModel;
            }
            
            return $this->surnamesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает SurnamesModel из СУБД
     * @return mixed
     */
    private function getSurname()
    {
        try {
            $finder = \Yii::$app->registry->get(SurnameSurnameFinder::class, ['surname'=>$this->surname]);
            $surnamesModel = $finder->find();
            
            return $surnamesModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
