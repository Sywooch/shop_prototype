<?php

namespace app\services;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\services\{AbstractBaseService,
    FrontendTrait,
    RegistrationEmailService};
use app\forms\UserRegistrationForm;
use app\finders\EmailEmailFinder;
use app\savers\ModelSaver;
use app\models\{EmailsModel,
    UsersModel};

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
     * @var string email регистрируемого пользователя
     */
    private $email = null;
    
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
                        $transaction  = \Yii::$app->db->beginTransaction();
                        
                        try {
                            $this->email = $this->form->email;
                            
                            $emailsModel = $this->getEmail();
                            
                            if ($emailsModel === null) {
                                $rawEmailsModel = new EmailsModel();
                                $rawEmailsModel->email = $this->email;
                                $saver = new ModelSaver([
                                    'model'=>$rawEmailsModel
                                ]);
                                $saver->save();
                                
                                $emailsModel = $this->getEmail();
                                
                                if ($emailsModel === null) {
                                    throw new ErrorException($this->emptyError('emailsModel'));
                                }
                            }
                            
                            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::SAVE]);
                            $rawUsersModel->id_email = $emailsModel->id;
                            $rawUsersModel->password = password_hash($this->form->password, PASSWORD_DEFAULT);
                            $rawUsersModel->validate();
                            $saver = new ModelSaver([
                                'model'=>$rawUsersModel
                            ]);
                            $saver->save();
                            
                            $mailService = new RegistrationEmailService();
                            $mailService->handle(['email'=>$this->email]);
                            
                            $transaction->commit();
                            
                            return Url::to(['/user/login']);
                        } catch (\Throwable $t) {
                            $transaction->rollBack();
                            throw $t;
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
    
    /**
     * Возвращает EmailsModel из СУБД
     * @return mixed
     */
    private function getEmail()
    {
        try {
            $finder = new EmailEmailFinder([
                'email'=>$this->email,
            ]);
            $emailsModel = $finder->find();
            
            return $emailsModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
