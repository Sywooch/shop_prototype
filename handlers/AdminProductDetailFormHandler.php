<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\services\GetAdminProductDetailFormWidgetConfigService;
use app\forms\AdminProductForm;
use app\widgets\AdminProductDetailFormWidget;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей товара
 */
class AdminProductDetailFormHandler extends AbstractBaseHandler
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
                    
                    $service = \Yii::$app->registry->get(GetAdminProductDetailFormWidgetConfigService::class, ['id'=>$form->id]);
                    $adminProductDetailFormWidgetConfig = $service->get();
                    
                    return AdminProductDetailFormWidget::widget($adminProductDetailFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
