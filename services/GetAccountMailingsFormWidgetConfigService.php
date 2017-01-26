<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsNotEmailFinder;
use app\forms\MailingForm;

/**
 * Возвращает массив конфигурации для виджета AccountMailingsFormWidget
 */
class GetAccountMailingsFormWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountMailingsFormWidget
     */
    private $accountMailingsFormWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            if (empty($this->accountMailingsFormWidgetArray)) {
                $dataArray = [];
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(MailingsNotEmailFinder::class, ['email'=>$user->email->email]);
                $mailingsModelArray = $finder->find();
                
                $dataArray['mailings'] = $mailingsModelArray;
                
                $dataArray['form'] = new MailingForm(['scenario'=>MailingForm::SAVE_ACC]);
                
                $dataArray['header'] = \Yii::t('base', 'Sign up now!');
                
                $dataArray['template'] = 'account-mailings-form.twig';
                
                $this->accountMailingsFormWidgetArray = $dataArray;
            }
            
            return $this->accountMailingsFormWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
