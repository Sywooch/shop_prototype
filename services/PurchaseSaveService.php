<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\{NotFoundHttpException,
    Request,
    Response};
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\{CartWidget,
    PurchaseSaveInfoWidget};
use app\models\PurchasesModel;

/**
 * Сохраняет новую покупку в корзину
 */
class PurchaseSaveService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для ToCartWidget
     */
    private $toCartWidgetArray = [];
    /**
     * @var CommentForm
     */
    private $form = null;
    
    /**
     * Обрабатывает запрос на сохранение новой покупки в корзину
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $this->form = new PurchaseForm(['scenario'=>PurchaseForm::SAVE]);
            
            if ($request->isAjax === true) {
                if ($this->form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($this->form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $key = HashHelper::createCartKey();
                    
                    $finder = new PurchasesSessionFinder([
                        'key'=>$key
                    ]);
                    $purchasesCollection = $finder->find();
                    
                    $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::SESSION_GET]);
                    $rawPurchasesModel->quantity = $this->form->quantity;
                    $rawPurchasesModel->id_color = $this->form->id_color;
                    $rawPurchasesModel->id_size = $this->form->id_size;
                    $rawPurchasesModel->id_product = $this->form->id_product;
                    $rawPurchasesModel->price = $this->form->price;
                    if ($rawPurchasesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawPurchasesModel->errors));
                    }
                    
                    $purchasesCollection->add($rawPurchasesModel);
                    
                    $saver = new SessionArraySaver([
                        'key'=>$key,
                        'models'=>$purchasesCollection->asArray()
                    ]);
                    $saver->save();
                    
                    return $this->getCartInfo();
                }
            }
            
            $dataArray = $this->getToCartWidgetArray($request);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ToCartWidget
     * @param Request $request данные запроса
     * @return array
     */
    private function getToCartWidgetArray(Request $request): array
    {
        try {
            if (empty($this->toCartWidgetArray)) {
                $dataArray = [];
                
                $dataArray['product'] = $this->getProductsModel($request);
                $dataArray['form'] = \Yii::configure($this->form, ['quantity'=>1]);
                $dataArray['view'] = 'to-cart-form.twig';
                
                $this->toCartWidgetArray = $dataArray;
            }
            
            return $this->toCartWidgetArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает HTML строку с обновленными данными корзины 
     * и сообщением об удачном сохранении товара в корзину
     * @return array
     */
    private function getCartInfo(): array
    {
        try {
            $dataArray = $this->getCartInfoAjax();
            
            $dataArray['successInfo'] = PurchaseSaveInfoWidget::widget(['view'=>'save-purchase-info.twig']);
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
