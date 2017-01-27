<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета UserRecoverySuccessWidget
 */
class GetUserRecoverySuccessWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UserRecoverySuccessWidget
     */
    private $userRecoverySuccessWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UserRecoverySuccessWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $email = $request['email'] ?? null;
            
            if (empty($email)) {
                throw new ErrorException($this->emptyError('email'));
            }
            
            if (empty($this->userRecoverySuccessWidgetArray)) {
                $dataArray = [];
                
                $dataArray['email'] = $email;
                $dataArray['header'] = \Yii::t('base', 'Password recovery');
                $dataArray['template'] = 'recovery-success.twig';
                
                $this->userRecoverySuccessWidgetArray = $dataArray;
            }
            
            return $this->userRecoverySuccessWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
