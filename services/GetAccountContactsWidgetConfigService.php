<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета AccountContactsWidget
 */
class GetAccountContactsWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета AccountContactsWidget
     */
    private $accountContactsWidgetArray = [];
    
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
            
            if (empty($this->accountContactsWidgetArray)) {
                $dataArray = [];
                
                $dataArray['user'] = \Yii::$app->user->identity;
                $dataArray['header'] = \Yii::t('base', 'Current contact details');
                $dataArray['template'] = 'account-contacts.twig';
                
                $this->accountContactsWidgetArray = $dataArray;
            }
            
            return $this->accountContactsWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
