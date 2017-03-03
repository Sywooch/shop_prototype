<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\ColorsFinder;
use app\forms\{AbstractBaseForm,
    ColorsForm};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем брендов
 */
class AdminColorsRequestHandler extends AbstractBaseHandler
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
                $finder = \Yii::$app->registry->get(ColorsFinder::class);
                $colorsModelArray = $finder->find();
                
                $colorsForm = new ColorsForm();
                
                $dataArray = [];
                
                $dataArray['adminColorsWidgetConfig'] = $this->adminColorsWidgetConfig($colorsModelArray, $colorsForm);
                $dataArray['adminCreateColorWidgetConfig'] = $this->adminCreateColorWidgetConfig($colorsForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateColorWidget
     * @param AbstractBaseForm $colorsFormCreate
     */
    private function adminCreateColorWidgetConfig(AbstractBaseForm $colorsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $colorsForm;
            $dataArray['header'] = \Yii::t('base', 'Create color');
            $dataArray['template'] = 'admin-create-color.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
