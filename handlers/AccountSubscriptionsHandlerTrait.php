<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\finders\{MailingsEmailFinder,
    MailingsNotEmailFinder};
use app\forms\MailingForm;

/**
 * Коллекция базовых методов
 */
trait AccountSubscriptionsHandlerTrait
{
    /**
     * Возвращает массив конфигурации для виджета AccountMailingsUnsubscribeWidget
     * @param string $email
     * @return array
     */
    private function unsubscribe(string $email): array
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
    private function subscribe(string $email): array
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
