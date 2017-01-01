<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\UserRegistrationForm;
use app\finders\EmailEmailFinder;

/**
 * Формирует массив данных для рендеринга страницы формы регистрации
 */
class UserRegistrationService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var array данные для UserRegistrationWidget
     */
    private $userRegistrationArray = [];
    /**
     * @var UserRegistrationForm
     */
    private $form = null;
    
    /**
     * Обрабатывает запрос на поиск и обработку данных для 
     * формирования HTML формы регистрации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $this->form = new UserRegistrationForm(['scenario'=>UserRegistrationForm::REGISTRATION]);
            
            if ($request->isPost) {
                if ($this->form->load($request->post()) === true) {
                    if ($this->form->validate() === true) {
                        
                        $finder = new EmailEmailFinder([
                            'email'=>$this->form->email,
                        ]);
                        $emailModel = $finder->find();
                        
                        if ($emailModel === null) {
                            
                        }
                        
                    }
                }
            }
            
            $dataArray = [];
            
            $dataArray['userConfig'] = $this->getUserArray();
            $dataArray['cartConfig'] = $this->getCartArray();
            $dataArray['currencyConfig'] = $this->getCurrencyArray();
            $dataArray['searchConfig'] = $this->getSearchArray();
            $dataArray['menuConfig'] = $this->getCategoriesArray();
            
            $dataArray['formConfig'] = $this->getUserRegistrationArray();
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UserRegistrationWidget
     * @return array
     */
    private function getUserRegistrationArray(): array
    {
        try {
            if (empty($this->userRegistrationArray)) {
                $dataArray = [];
                
                $dataArray['form'] = $this->form;
                $dataArray['view'] = 'registration-form.twig';
                
                $this->userRegistrationArray = $dataArray;
            }
            
            return $this->userRegistrationArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
