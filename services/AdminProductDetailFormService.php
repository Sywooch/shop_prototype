<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetAdminProductDetailFormWidgetConfigService};
use app\forms\AdminProductForm;
use app\widgets\AdminProductDetailFormWidget;

/**
 * Формирует массив данных для рендеринга страницы 
 * с формой редактирования деталей товара
 */
class AdminProductDetailFormService extends AbstractBaseService
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
           $form = new AdminProductForm(['scenario'=>AdminProductForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    /*$service = \Yii::$app->registry->get(GetAdminProductDetailFormWidgetConfigService::class);
                    $adminProductDetailFormWidgetConfig = $service->handle(['id'=>$form->id]);*/
                    
                    return AdminProductDetailFormWidget::widget($adminProductDetailFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
