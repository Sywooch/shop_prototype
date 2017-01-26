<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета PasswordGenerateSuccessWidget
 */
class GetPasswordGenerateSuccessWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета PasswordGenerateSuccessWidget
     */
    private $passwordGenerateSuccessWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета PasswordGenerateSuccessWidget
     * @param $request
     * @return array
     */
    public function handle($request): array
    {
        try {
            $tempPassword = $request['tempPassword'] ?? null;
            
            if (empty($tempPassword)) {
                throw new ErrorException($this->emptyError('tempPassword'));
            }
            
            if (empty($this->passwordGenerateSuccessWidgetArray)) {
                $dataArray = [];
                
                $dataArray['tempPassword'] = $tempPassword;
                
                 $dataArray['header'] = \Yii::t('base', 'Password recovery');
                
                $dataArray['template'] = 'generate-success.twig';
                
                $this->passwordGenerateSuccessWidgetArray = $dataArray;
            }
            
            return $this->passwordGenerateSuccessWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
