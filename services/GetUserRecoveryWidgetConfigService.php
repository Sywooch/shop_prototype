<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;
use app\forms\RecoveryPasswordForm;

/**
 * Возвращает массив конфигурации для виджета UserRecoveryWidget
 */
class GetUserRecoveryWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета UserRecoveryWidget
     */
    private $userRecoveryWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета UserRecoveryWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->userRecoveryWidgetArray)) {
                $dataArray = [];
                
                $dataArray['form'] = new RecoveryPasswordForm(['scenario'=>RecoveryPasswordForm::GET]);
                $dataArray['view'] = 'recovery-form.twig';
                
                $this->userRecoveryWidgetArray = $dataArray;
            }
            
            return $this->userRecoveryWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
