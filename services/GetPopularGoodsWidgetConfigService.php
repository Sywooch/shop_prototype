<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\PopularProductsFinder;

/**
 * Возвращает массив конфигурации для виджета PopularGoodsWidget
 */
class GetPopularGoodsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета PopularGoodsWidget
     */
    private $popularGoodsWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->popularGoodsWidgetArray)) {
                $dataArray = [];
                
                $dataArray['header'] = \Yii::t('base', 'Popular goods');
                
                $finder = \Yii::$app->registry->get(PopularProductsFinder::class);
                $dataArray['goods'] = $finder->find();
                
                $dataArray['template'] = 'popular-goods.twig';
                
                $this->popularGoodsWidgetArray = $dataArray;
            }
            
            return $this->popularGoodsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
