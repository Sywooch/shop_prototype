<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\UserLoginForm;
use app\finders\UserEmailFinder;

/**
 * Формирует массив данных для рендеринга страницы формы аутентификации
 */
class UserLoginService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для UserLoginWidget
     */
    private $userLoginArray = [];
    /**
     * @var UserLoginForm
     */
    private $form = null;
    
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы аутентификации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $this->form = new UserLoginForm(['scenario'=>UserLoginForm::LOGIN]);
            
            if ($request->isAjax === true) {
                if ($this->form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($this->form);
                }
            }
            
            if ($request->isPost) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        $finder = \Yii::$app->registry->get(UserEmailFinder::class, ['email'=>$this->form->email]);
                        $usersModel = $finder->find();
                        if (empty($usersModel)) {
                            throw new ErrorException($this->emptyError('usersModel'));
                        }
                        \Yii::$app->user->login($usersModel);
                        return Url::to(['/products-list/index']);
                    }
                }
            }
            
            $dataArray = [];
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $dataArray['formConfig'] = $this->getUserLoginArray();
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserLoginWidget
     * @return array
     */
    private function getUserLoginArray(): array
    {
        try {
            if (empty($this->userLoginArray)) {
                $dataArray = [];
                
                $dataArray['form'] = $this->form;
                $dataArray['view'] = 'login-form.twig';
                
                $this->userLoginArray = $dataArray;
            }
            
            return $this->userLoginArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
