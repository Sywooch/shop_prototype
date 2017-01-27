<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService,
    GetProductDetailModelService};
use app\finders\SimilarFinder;

/**
 * Возвращает массив конфигурации для виджета SeeAlsoWidget
 */
class GetSeeAlsoWidgetSimilarConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета SeeAlsoWidget
     */
    private $seeAlsoWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета SeeAlsoWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            if (empty($this->seeAlsoWidgetArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetProductDetailModelService::class);
                $productsModel = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(SimilarFinder::class, ['product'=>$productsModel]);
                $similarArray = $finder->find();
                
                $dataArray['products'] = $similarArray;
                
                $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class);
                $dataArray['currency'] = $service->handle();
                
                $dataArray['header'] = \Yii::t('base', 'Similar products');
                $dataArray['template'] = 'see-also.twig';
                
                $this->seeAlsoWidgetArray = $dataArray;
            }
            
            return $this->seeAlsoWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
