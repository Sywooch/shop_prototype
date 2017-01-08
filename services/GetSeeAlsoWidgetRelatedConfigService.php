<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCurrentCurrencyModelService,
    GetProductDetailModelService};
use app\finders\RelatedFinder;

/**
 * Возвращает массив конфигурации для виджета SeeAlsoWidget
 */
class GetSeeAlsoWidgetRelatedConfigService extends AbstractBaseService
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
                
                $service = new GetProductDetailModelService();
                $productsModel = $service->handle($request);
                
                $finder = \Yii::$app->registry->get(RelatedFinder::class, ['product'=>$productsModel]);
                $similarArray = $finder->find();
                
                $dataArray['products'] = $similarArray;
                
                $service = new GetCurrentCurrencyModelService();
                $dataArray['currency'] = $service->handle();
                
                $dataArray['header'] = \Yii::t('base', 'Related products');
                $dataArray['view'] = 'see-also.twig';
                
                $this->seeAlsoWidgetArray = $dataArray;
            }
            
            return $this->seeAlsoWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
