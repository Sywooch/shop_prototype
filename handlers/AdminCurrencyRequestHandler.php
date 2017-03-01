<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\SizesFinder;
use app\forms\{AbstractBaseForm,
    CurrencyForm};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем брендов
 */
class AdminCurrencyRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(SizesFinder::class);
                $sizesModelArray = $finder->find();
                
                $sizesFormDelete = new CurrencyForm(['scenario'=>CurrencyForm::DELETE]);
                $sizesFormCreate = new CurrencyForm(['scenario'=>CurrencyForm::CREATE]);
                
                $dataArray = [];
                
                $dataArray['adminSizesWidgetConfig'] = $this->adminSizesWidgetConfig($sizesModelArray, $sizesFormDelete);
                $dataArray['adminCreateSizeWidgetConfig'] = $this->adminCreateSizeWidgetConfig($sizesFormCreate);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateSizeWidget
     * @param AbstractBaseForm $sizesFormCreate
     */
    private function adminCreateSizeWidgetConfig(AbstractBaseForm $sizesFormCreate): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $sizesFormCreate;
            $dataArray['header'] = \Yii::t('base', 'Create size');
            $dataArray['template'] = 'admin-create-size.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
