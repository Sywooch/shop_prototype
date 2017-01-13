<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\AbstractBaseService;
use app\forms\CustomerInfoForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;

/**
 * Сохраняет данные покупателя
 */
class CartCheckoutPostService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение данных покупателя
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($form);
                }
            }
            
            if ($request->isPost === true) {
                if ($form->load($request->post()) === true) {
                    if ($form->validate() === true) {
                        
                        $key = HashHelper::createCartCustomerKey();
                        
                        $saver = new SessionModelSaver([
                            'key'=>$key,
                            'model'=>$form
                        ]);
                        $saver->save();
                        
                        return Url::to(['/cart/confirm']);
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
