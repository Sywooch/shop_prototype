<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetAdminOrderDetailFormWidgetConfigService};
use app\forms\AdminChangeOrderForm;
use app\widgets\AdminOrderDetailFormWidget;

/**
 * Формирует массив данных для рендеринга страницы 
 * с формой редактирования деталей заказа
 */
class AdminOrderDetailFormService extends AbstractBaseService
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
           $form = new AdminChangeOrderForm(['scenario'=>AdminChangeOrderForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $service = \Yii::$app->registry->get(GetAdminOrderDetailFormWidgetConfigService::class);
                    $adminOrderDetailFormWidgetConfig = $service->handle(['id'=>$form->id]);
                    
                    return AdminOrderDetailFormWidget::widget($adminOrderDetailFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
