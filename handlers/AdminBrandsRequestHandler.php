<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\BrandsFinder;
use app\forms\{AbstractBaseForm,
    BrandsForm};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем брендов
 */
class AdminBrandsRequestHandler extends AbstractBaseHandler
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
                $finder = \Yii::$app->registry->get(BrandsFinder::class);
                $brandsModelArray = $finder->find();
                
                $brandsForm = new BrandsForm();
                
                $dataArray = [];
                
                $dataArray['adminBrandsWidgetConfig'] = $this->adminBrandsWidgetConfig($brandsModelArray, $brandsForm);
                $dataArray['adminCreateBrandWidgetConfig'] = $this->adminCreateBrandWidgetConfig($brandsForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateBrandWidget
     * @param AbstractBaseForm $brandsFormCreate
     */
    private function adminCreateBrandWidgetConfig(AbstractBaseForm $brandsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $brandsForm;
            $dataArray['header'] = \Yii::t('base', 'Create brand');
            $dataArray['template'] = 'admin-create-brand.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
