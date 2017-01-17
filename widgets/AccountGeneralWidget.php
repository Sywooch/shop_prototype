<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\{CurrencyModel,
    UsersModel};

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AccountGeneralWidget extends AbstractBaseWidget
{
    /**
     * @var UsersModel
     */
    private $user;
    /**
     * @var array PurchasesModel
     */
    private $purchases;
    /**
     * @var CurrencyModel
     */
    private $currency;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->user)) {
                throw new ErrorException($this->emptyError('user'));
            }
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['userHeader'] = \Yii::t('base', 'Current contact details');
            $renderArray['userOrders'] = \Yii::t('base', 'Current orders');
            
            $renderArray['user']['email'] = !empty($this->user->id_email) ? $this->user->email->email : null;
            $renderArray['user']['emailHeader'] = \Yii::t('base', 'Email');
            
            $renderArray['user']['name'] = !empty($this->user->id_name) ? $this->user->name->name: null;
            $renderArray['user']['nameHeader'] = \Yii::t('base', 'Name');
            
            $renderArray['user']['surname'] = !empty($this->user->id_surname) ? $this->user->surname->surname: null;
            $renderArray['user']['surnameHeader'] = \Yii::t('base', 'Surname');
            
            $renderArray['user']['phone'] = !empty($this->user->id_phone) ? $this->user->phone->phone : null;
            $renderArray['user']['phoneHeader'] = \Yii::t('base', 'Phone');
            
            $renderArray['user']['address'] = !empty($this->user->id_address) ? $this->user->address->address : null;
            $renderArray['user']['addressHeader'] = \Yii::t('base', 'Address');
            
            $renderArray['user']['city'] = !empty($this->user->id_city) ? $this->user->city->city : null;
            $renderArray['user']['cityHeader'] = \Yii::t('base', 'City');
            
            $renderArray['user']['country'] = !empty($this->user->id_country) ? $this->user->country->country : null;
            $renderArray['user']['countryHeader'] = \Yii::t('base', 'Country');
            
            $renderArray['user']['postcode'] = !empty($this->user->id_postcode) ? $this->user->postcode->postcode : null;
            $renderArray['user']['postcodeHeader'] = \Yii::t('base', 'Postcode');
            
            if (!empty($this->purchases)) {
                $purchases = array_filter($this->purchases, function($item) {
                    return ((int) $item->canceled === 0 && (int) $item->shipped === 0) ? true : false;
                });
                
                if (!empty($purchases)) {
                    foreach ($purchases as $purchase) {
                        $set = [];
                        $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                        $set['linkText'] = Html::encode($purchase->product->name);
                        $set['short_description'] = $purchase->product->short_description;
                        $set['quantity'] = sprintf('%s: %s', \Yii::t('base', 'Quantity'), $purchase->quantity);
                        $set['price'] = sprintf('%s: %s', \Yii::t('base', 'Price'), \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchange_rate, 2) . ' ' . $this->currency->code);
                        $set['color'] = sprintf('%s: %s', \Yii::t('base', 'Color'), $purchase->color->color);
                        $set['size'] = sprintf('%s: %s', \Yii::t('base', 'Size'), $purchase->size->size);
                        if (!empty($purchase->product->images)) {
                            $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $purchase->product->images) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                            if (!empty($imagesArray)) {
                                $set['image'] = Html::img(\Yii::getAlias('@imagesweb/' . $purchase->product->images . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]), ['height'=>200]);
                            }
                        }
                        $set['status'] = sprintf('%s: %s', \Yii::t('base', 'Status'), \Yii::t('base', 'Processed'));
                        $renderArray['purchases'][] = $set;
                    }
                }
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает UsersModel свойству AccountGeneralWidget::user
     * @param UsersModel $user
     */
    public function setUser(UsersModel $user)
    {
        try {
            $this->user = $user;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesModel свойству AccountGeneralWidget::purchases
     * @param array $purchases
     */
    public function setPurchases(array $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyModel свойству AccountGeneralWidget::currency
     * @param CurrencyModel $currency
     */
    public function setCurrency(CurrencyModel $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
