<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\{MailingsEmailFinder,
    MailingsNotEmailFinder};
use app\forms\MailingForm;

/**
 * Обрабатывает запрос на данные 
 * для рендеринга страницы с данными о подписках
 */
class AccountChangeSubscriptionsRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы с настройками аккаунта
     * @param array $request
     */
    public function handle($request=null)
    {
        try {
            if (empty($this->dataArray)) {
                $usersModel = \Yii::$app->user->identity;
                $email = $usersModel->email->email;
                
                $dataArray = [];
                
                $dataArray['accountMailingsUnsubscribeWidgetConfig'] = $this->accountMailingsUnsubscribeWidgetConfig($email);
                $dataArray['accountMailingsFormWidgetConfig'] = $this->accountMailingsFormWidgetConfig($email);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsUnsubscribeWidget
     * @param string $email
     * @return array
     */
    private function accountMailingsUnsubscribeWidgetConfig(string $email): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                'email'=>$email
            ]);
            $dataArray['mailings'] = $finder->find();
            
            $dataArray['form'] = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE_ACC]);
            $dataArray['header'] = \Yii::t('base', 'Current subscriptions');
            $dataArray['template'] = 'account-mailings-unsubscribe.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsFormWidget
     * @param string $email
     * @return array
     */
    private function accountMailingsFormWidgetConfig(string $email): array
    {
        try {
            $dataArray = [];
            
            $finder = \Yii::$app->registry->get(MailingsNotEmailFinder::class, [
                'email'=>$email
            ]);
            $dataArray['mailings'] = $finder->find();
            
            $dataArray['form'] = new MailingForm(['scenario'=>MailingForm::SAVE_ACC]);
            $dataArray['header'] = \Yii::t('base', 'Sign up now!');
            $dataArray['template'] = 'account-mailings-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
