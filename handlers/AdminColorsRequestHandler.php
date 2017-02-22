<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\ColorsFinder;

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
                
                $dataArray = [];
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminColorsWidget
     * @param AbstractBaseForm $colorsForm
     */
    private function adminColorsWidgetConfig(AbstractBaseForm $colorsForm): array
    {
        try {
            $dataArray = [];
            
            
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
