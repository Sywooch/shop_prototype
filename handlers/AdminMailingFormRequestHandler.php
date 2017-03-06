<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    AdminMailingForm};
use app\widgets\AdminMailingFormWidget;
use app\finders\MailingIdFinder;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей почтовой рассылки
 */
class AdminMailingFormRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
           $form = new AdminMailingForm(['scenario'=>AdminMailingForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $finder = \Yii::$app->registry->get(MailingIdFinder::class, [
                        'id'=>$form->id
                    ]);
                    $mailingsModel = $finder->find();
                    if (empty($mailingsModel)) {
                        throw new ErrorException($this->emptyError('mailingsModel'));
                    }
                    
                    $mailingForm = new AdminMailingForm();
                    
                    $adminMailingFormWidgetConfig = $this->adminMailingFormWidgetConfig($mailingsModel, $mailingForm);
                    
                    return AdminMailingFormWidget::widget($adminMailingFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminMailingFormWidget
     * @param Model $mailingsModel
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function adminMailingFormWidgetConfig(Model $mailingsModel, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailing'] = $mailingsModel;
            $dataArray['form'] = $mailingForm;
            $dataArray['template'] = 'admin-mailing-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
