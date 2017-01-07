<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\CategoriesFinder;

/**
 * Возвращает массив конфигурации для виджета CategoriesMenuWidget
 */
class GetCategoriesMenuWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета CategoriesMenuWidget
     */
    private $categoriesMenuWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesMenuWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->categoriesMenuWidgetArray)) {
                $dataArray = [];
                
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesArray = $finder->find();
                
                if (empty($categoriesArray)) {
                    throw new ErrorException($this->emptyError('categoriesArray'));
                }
                
                $dataArray['categories'] = $categoriesArray;
            
                $this->categoriesMenuWidgetArray = $dataArray;
            }
            
            return $this->categoriesMenuWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
