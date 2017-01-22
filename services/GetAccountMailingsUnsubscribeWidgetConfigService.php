<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\finders\MailingsEmailFinder;
use app\forms\MailingForm;

/**
 * Возвращает массив конфигурации для виджета AccountMailingsUnsubscribeWidget
 */
class GetAccountMailingsUnsubscribeWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountMailingsUnsubscribeWidget
     */
    private $accountMailingsUnsubscribeWidgetArray = [];
    
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
            
            if (empty($this->accountMailingsUnsubscribeWidgetArray)) {
                $dataArray = [];
                
                $user = \Yii::$app->user->identity;
                
                $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, ['email'=>$user->email->email]);
                $dataArray['mailings'] = $finder->find();
                
                $dataArray['form'] = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE_ACC]);
                
                $dataArray['view'] = 'account-mailings-unsubscribe.twig';
                
                $this->accountMailingsUnsubscribeWidgetArray = $dataArray;
            }
            
            return $this->accountMailingsUnsubscribeWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
