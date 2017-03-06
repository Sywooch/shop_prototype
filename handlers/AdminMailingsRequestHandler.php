<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\finders\AdminMailingsFinder;
use app\forms\{AbstractBaseForm,
    AdminMailingForm};
use app\helpers\HashHelper;

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем валют
 */
class AdminMailingsRequestHandler extends AbstractBaseHandler
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
                $finder = \Yii::$app->registry->get(AdminMailingsFinder::class);
                $mailingsModelArray = $finder->find();
                
                $mailingsForm = new AdminMailingForm();
                
                $dataArray = [];
                
                $dataArray['adminMailingsWidgetConfig'] = $this->adminMailingsWidgetConfig($mailingsModelArray, $mailingsForm);
                $dataArray['adminCreateMailingWidgetConfig'] = $this->adminCreateMailingWidgetConfig($mailingsForm);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateMailingWidget
     * @param AbstractBaseForm $mailingsFormCreate
     */
    private function adminCreateMailingWidgetConfig(AbstractBaseForm $mailingsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $mailingsForm;
            $dataArray['header'] = \Yii::t('base', 'Add mailing');
            $dataArray['template'] = 'admin-create-mailing.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
