<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\User;
use app\models\CurrencyInterface;
use app\forms\AbstractBaseForm;
use app\collections\{CollectionInterface,
    PaginationInterface,
    PurchasesCollectionInterface};
use app\helpers\DateHelper;

/**
 * Коллекция базовых методов
 */
trait ConfigHandlerTrait
{
    /**
     * Возвращает массив конфигурации для виджета UserInfoWidget
     * @param yii\web\User $user
     * @return array
     */
    private function userInfoWidgetConfig(User $webUser): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = $webUser;
            $dataArray['template'] = 'user-info.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @patram PurchasesCollectionInterface $ordersCollection
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function shortCartWidgetConfig(PurchasesCollectionInterface $ordersCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $ordersCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CurrencyWidget
     * @param array $currencyArray массив доступных валют
     * @param AbstractBaseForm $changeCurrencyForm
     * @return array
     */
    private function currencyWidgetConfig(array $currencyArray, AbstractBaseForm $changeCurrencyForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($currencyArray, 'code');
            $dataArray['currency'] = ArrayHelper::map($currencyArray, 'id', 'code');
            
            $dataArray['form'] = $changeCurrencyForm;
            $dataArray['header'] = \Yii::t('base', 'Currency');
            $dataArray['template'] = 'currency-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета SearchWidget
     * @param string $searchKey искомая фраза
     * @return array
     */
    private function searchWidgetConfig(string $searchKey=''): array
    {
        try {
            $dataArray = [];
            
            $dataArray['text'] = $searchKey;
            $dataArray['template'] = 'search.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesMenuWidget
     * @param array $categoriesModelArray
     * @return array
     */
    private function categoriesMenuWidgetConfig(array $categoriesModelArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['categories'] = $categoriesModelArray;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsUnsubscribeWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function accountMailingsUnsubscribeWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['form'] = $mailingForm;
            $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
            $dataArray['template'] = 'admin-mailings-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsFormWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function accountMailingsFormWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['form'] = $mailingForm;
            $dataArray['header'] = \Yii::t('base', 'Sign up now!');
            $dataArray['template'] = 'admin-mailings-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета OrdersFiltersWidget
     * @param array $sortingTypesArray
     * @param array $statusesArray
     * @param AbstractBaseForm $ordersFiltersForm
     * @return array
     */
    private function оrdersFiltersWidgetConfig(array $sortingTypesArray, array $statusesArray, AbstractBaseForm $ordersFiltersForm): array
    {
        try {
            $dataArray = [];
            
            asort($sortingTypesArray, SORT_STRING);
            $dataArray['sortingTypes'] = $sortingTypesArray;
            
            asort($statusesArray, SORT_STRING);
            $dataArray['statuses'] = ArrayHelper::merge([''=>\Yii::t('base', 'All')], $statusesArray);
            
            if (empty($ordersFiltersForm->sortingType)) {
                foreach ($sortingTypesArray as $key=>$val) {
                    if ($key === \Yii::$app->params['sortingType']) {
                        $ordersFiltersForm->sortingType = $key;
                    }
                }
            }
            
            $todayDate = DateHelper::getToday00();
            
            if (empty($ordersFiltersForm->dateFrom)) {
                $ordersFiltersForm->dateFrom = $todayDate;
            }
            if (empty($ordersFiltersForm->dateTo)) {
                $ordersFiltersForm->dateTo = $todayDate;
            }
            
            $ordersFiltersForm->url = Url::current();
            
            $dataArray['form'] = $ordersFiltersForm;
            $dataArray['header'] = \Yii::t('base', 'Filters');
            $dataArray['template'] = 'orders-filters.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета PaginationWidget
     * @param PaginationInterface $pagination
     * @return array
     */
    private function paginationWidgetConfig(PaginationInterface $pagination): array
    {
        try {
            $dataArray = [];
            
            $dataArray['pagination'] = $pagination;
            $dataArray['template'] = 'pagination.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartWidget
     * @param PurchasesCollectionInterface $ordersCollection
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function shortCartWidgetAjaxConfig(PurchasesCollectionInterface $ordersCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $ordersCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart-ajax.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CartWidget
     * @param PurchasesCollectionInterface $ordersCollection
     * @param CurrencyInterface $currentCurrencyModel
     * @param AbstractBaseForm $purchaseForm
     * @return array
     */
    private function cartWidgetConfig(PurchasesCollectionInterface $ordersCollection, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $purchaseForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $ordersCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $purchaseForm;
            $dataArray['header'] = \Yii::t('base', 'Selected products');
            $dataArray['template'] = 'cart.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ShortCartRedirectWidget
     * @param PurchasesCollectionInterface $purchasesCollection
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function shortCartRedirectWidgetConfig(PurchasesCollectionInterface $purchasesCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['purchases'] = $purchasesCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'short-cart-redirect.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета EmptyProductsWidget
     * @return array
     */
    private function emptyProductsWidgetConfig(): array
    {
        try {
            $dataArray = [];
            
            $dataArray['template'] = 'empty-products.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета ProductsWidget
     * @param CollectionInterface $productsCollection
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function productsWidgetConfig(CollectionInterface $productsCollection, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['products'] = $productsCollection;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'products-list.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UnsubscribeEmptyWidget
     * @param string $email
     * @return array
     */
    private function unsubscribeEmptyWidgetConfig(string $email): array
    {
        try {
            $dataArray = [];
            
            $dataArray['email'] = $email;
            $dataArray['template'] = 'unsubscribe-empty.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminAddProductFormWidget
     * @param array $categoriesArray
     * @param array $colorsArray
     * @param array $sizesArray
     * @param array $brandsArray
     * @param AbstractBaseForm $adminProductForm
     * @return array
     */
    private function adminAddProductFormWidgetConfig(array $categoriesArray, array $colorsArray, array $sizesArray, array $brandsArray, AbstractBaseForm $adminProductForm): array
    {
        try {
            $dataArray = [];
            
            ArrayHelper::multisort($categoriesArray, 'name');
            $categoriesArray = ArrayHelper::map($categoriesArray, 'id', 'name');
            $dataArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesArray);
            
            $dataArray['subcategory'] = [\Yii::$app->params['formFiller']];
            
            ArrayHelper::multisort($colorsArray, 'color');
            $dataArray['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
            
            ArrayHelper::multisort($sizesArray, 'size');
            $dataArray['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
            
            ArrayHelper::multisort($brandsArray, 'brand');
            $brandsArray = ArrayHelper::map($brandsArray, 'id', 'brand');
            $dataArray['brands'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $brandsArray);
            
            $dataArray['form'] = $adminProductForm;
            $dataArray['template'] = 'admin-add-product-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCategoriesWidget
     * @param array $categoriesModelArray
     * @param AbstractBaseForm $categoriesForm
     * @param AbstractBaseForm $subcategoryForm
     * @return array
     */
    private function adminCategoriesWidgetConfig(array $categoriesModelArray, AbstractBaseForm $categoriesForm, AbstractBaseForm $subcategoryForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['categories'] = $categoriesModelArray;
            $dataArray['categoriesForm'] = $categoriesForm;
            $dataArray['subcategoryForm'] = $subcategoryForm;
            $dataArray['header'] = \Yii::t('base', 'Product categories');
            $dataArray['template'] = 'admin-categories.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета CategoriesOptionWidget
     * @param array $categoriesModelArray
     */
    private function categoriesOptionWidgetConfig(array $categoriesModelArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['categories'] = $categoriesModelArray;
            $dataArray['template'] = 'categories-option.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminBrandsWidget
     * @param array $brandsModelArray
     * @param AbstractBaseForm $brandsForm
     * @return array
     */
    private function adminBrandsWidgetConfig(array $brandsModelArray, AbstractBaseForm $brandsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['brands'] = $brandsModelArray;
            $dataArray['header'] = \Yii::t('base', 'Brands');
            $dataArray['brandsForm'] = $brandsForm;
            $dataArray['template'] = 'admin-brands.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminColorsWidget
     * @param array $colorsModelArray
     * @param AbstractBaseForm $colorsForm
     */
    private function adminColorsWidgetConfig(array $colorsModelArray, AbstractBaseForm $colorsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['colors'] = $colorsModelArray;
            $dataArray['form'] = $colorsForm;
            $dataArray['header'] = \Yii::t('base', 'Colors');
            $dataArray['template'] = 'admin-colors.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminSizesWidget
     * @param array $sizesModelArray
     * @param AbstractBaseForm $sizesForm
     */
    private function adminSizesWidgetConfig(array $sizesModelArray, AbstractBaseForm $sizesForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['sizes'] = $sizesModelArray;
            $dataArray['form'] = $sizesForm;
            $dataArray['header'] = \Yii::t('base', 'Sizes');
            $dataArray['template'] = 'admin-sizes.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountContactsWidget
     * @param Model $usersModel
     * @return array
     */
    private function accountContactsWidgetConfig(Model $usersModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['user'] = $usersModel;
            $dataArray['header'] = \Yii::t('base', 'Current contact details');
            $dataArray['template'] = 'account-contacts.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountCurrentOrdersWidget
     * @param array $purchasesArray
     * @patram CurrencyInterface $currentCurrencyModel объект текущей валюты
     * @return array
     */
    private function accountCurrentOrdersWidgetConfig(array $purchasesArray, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Current orders');
            $dataArray['purchases'] = $purchasesArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['template'] = 'account-current-orders.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета MailingsWidget
     * @param array $mailingsArray
     * @return array
     */
    private function accountMailingsWidgetConfig(array $mailingsArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsArray;
            $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
            $dataArray['template'] = 'mailings.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminUserDetailBreadcrumbsWidget
     * @param Model $usersModel
     * @return array
     */
    private function adminUserDetailBreadcrumbsWidgetConfig(Model $usersModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['usersModel'] = $usersModel;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminUserMenuWidget
     * @param Model $usersModel
     * @return array
     */
    private function adminUserMenuWidgetConfig(Model $usersModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['usersModel'] = $usersModel;
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountOrdersWidget
     * @param array $ordersArray массив PurchasesModel
     * @patram AbstractBaseForm $purchaseForm
     * @param CurrencyInterface $currentCurrencyModel
     * @return array
     */
    private function accountOrdersWidgetConfig(array $ordersArray, AbstractBaseForm $purchaseForm, CurrencyInterface $currentCurrencyModel): array
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Orders');
            $dataArray['purchases'] = $ordersArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $purchaseForm;
            $dataArray['template'] = 'orders.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountChangeDataWidget
     * @param AbstractBaseForm $userUpdateForm
     * @param Model $usersModel
     * @return array
     */
    private function accountChangeDataWidgetConfig(AbstractBaseForm $userUpdateForm, Model $usersModel): array
    {
        try {
            $userUpdateForm->name = !empty($usersModel->id_name) ? $usersModel->name->name : null;
            $userUpdateForm->surname = !empty($usersModel->id_surname) ? $usersModel->surname->surname: null;
            $userUpdateForm->phone = !empty($usersModel->id_phone) ? $usersModel->phone->phone : null;
            $userUpdateForm->address = !empty($usersModel->id_address) ? $usersModel->address->address : null;
            $userUpdateForm->city = !empty($usersModel->id_city) ? $usersModel->city->city : null;
            $userUpdateForm->country = !empty($usersModel->id_country) ? $usersModel->country->country : null;
            $userUpdateForm->postcode = !empty($usersModel->id_postcode) ? $usersModel->postcode->postcode : null;
            
            $dataArray = [];
            
            $dataArray['form'] = $userUpdateForm;
            $dataArray['header'] = \Yii::t('base', 'Change data');
            $dataArray['template'] = 'change-user-data-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountChangePasswordWidget
     * @param AbstractBaseForm $userChangePasswordForm
     * @return array
     */
    private function accountChangePasswordWidgetConfig(AbstractBaseForm $userChangePasswordForm)
    {
        try {
            $dataArray = [];
            
            $dataArray['header'] = \Yii::t('base', 'Change password');
            $dataArray['form'] = $userChangePasswordForm;
            $dataArray['template'] = 'password-change-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminUserMailingsUnsubscribeWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function adminUserMailingsUnsubscribeWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = $this->accountMailingsUnsubscribeWidgetConfig($mailingsArray, $mailingForm);
            $dataArray['template'] = 'admin-mailings-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminUserMailingsFormWidget
     * @param array $mailingsArray
     * @param AbstractBaseForm $mailingForm
     * @return array
     */
    private function adminUserMailingsFormWidgetConfig(array $mailingsArray, AbstractBaseForm $mailingForm): array
    {
        try {
            $dataArray = $this->accountMailingsFormWidgetConfig($mailingsArray, $mailingForm);
            $dataArray['template'] = 'admin-mailings-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCurrencyWidget
     * @param array $currencyModelArray
     * @param AbstractBaseForm $currencyForm
     */
    private function adminCurrencyWidgetConfig(array $currencyModelArray, AbstractBaseForm $currencyForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['currency'] = $currencyModelArray;
            $dataArray['form'] = $currencyForm;
            $dataArray['header'] = \Yii::t('base', 'Currency');
            $dataArray['template'] = 'admin-currency.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminDeliveriesWidget
     * @param array $deliveriesModelArray
     * @param CurrencyInterface $currentCurrencyModel
     * @param AbstractBaseForm $deliveriesForm
     */
    private function adminDeliveriesWidgetConfig(array $deliveriesModelArray, CurrencyInterface $currentCurrencyModel, AbstractBaseForm $deliveriesForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['deliveries'] = $deliveriesModelArray;
            $dataArray['currency'] = $currentCurrencyModel;
            $dataArray['form'] = $deliveriesForm;
            $dataArray['header'] = \Yii::t('base', 'Deliveries');
            $dataArray['template'] = 'admin-deliveries.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminPaymentsWidget
     * @param array $paymentsModelArray
     * @param AbstractBaseForm $paymentsForm
     */
    private function adminPaymentsWidgetConfig(array $paymentsModelArray, AbstractBaseForm $paymentsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['payments'] = $paymentsModelArray;
            $dataArray['form'] = $paymentsForm;
            $dataArray['header'] = \Yii::t('base', 'Payments');
            $dataArray['template'] = 'admin-payments.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminMailingsWidget
     * @param array $mailingsModelArray
     * @param AbstractBaseForm $mailingsForm
     */
    private function adminMailingsWidgetConfig(array $mailingsModelArray, AbstractBaseForm $mailingsForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['mailings'] = $mailingsModelArray;
            $dataArray['form'] = $mailingsForm;
            $dataArray['header'] = \Yii::t('base', 'Mailings');
            $dataArray['template'] = 'admin-mailings.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
