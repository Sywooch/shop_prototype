<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\services\AbstractBaseService;
use app\finders\SphinxFinder;

/**
 * Возвращает объект ProductsCollection
 */
class GetSphinxArrayService extends AbstractBaseService
{
    /**
     * @var array ID товаров, соответствующих поисковому запросу
     */
    private $sphinxArray = [];
    
    /**
     * Возвращает array
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->sphinxArray)) {
                $search = $request->get(\Yii::$app->params['searchKey']);
                if (empty($search)) {
                    throw new ErrorException($this->emptyError(\Yii::$app->params['searchKey']));
                }
                
                $finder = \Yii::$app->registry->get(SphinxFinder::class, ['search'=>$search]);
                $sphinxArray = $finder->find();
                
                if (!empty($sphinxArray)) {
                    $this->sphinxArray = ArrayHelper::getColumn($sphinxArray, 'id');
                }
            }
            
            return $this->sphinxArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
