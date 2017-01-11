<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    GetShortCartWidgetAjaxConfigService};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\PurchaseSaveInfoWidget;
use app\models\PurchasesModel;

/**
 * Сохраняет новую покупку в корзине
 */
class CartUpdateService extends AbstractBaseService
{
    /**
     * Обрабатывает запрос на сохранение новой покупки в корзине
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PurchaseForm(['scenario'=>PurchaseForm::UPDATE]);
            
            if ($request->isPost === true) {
                if ($form->load($request->post()) === true) {
                    if ($form->validate() === true) {
                        $key = HashHelper::createCartKey();
                        
                        $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, ['key'=>$key]);
                        $purchasesCollection = $finder->find();
                        
                        $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
                        $rawPurchasesModel->quantity = $form->quantity;
                        $rawPurchasesModel->id_color = $form->id_color;
                        $rawPurchasesModel->id_size = $form->id_size;
                        $rawPurchasesModel->id_product = $form->id_product;
                        if ($rawPurchasesModel->validate() === false) {
                            throw new ErrorException($this->modelError($rawPurchasesModel->errors));
                        }
                        
                        $purchasesCollection->update($rawPurchasesModel);
                        
                        $saver = new SessionArraySaver([
                            'key'=>$key,
                            'models'=>$purchasesCollection->asArray()
                        ]);
                        $saver->save();
                        
                        return Url::to(['/cart/index']);;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
