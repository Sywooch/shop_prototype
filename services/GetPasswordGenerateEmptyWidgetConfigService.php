<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\AbstractBaseService;

/**
 * Возвращает массив конфигурации для виджета PasswordGenerateEmptyWidget
 */
class GetPasswordGenerateEmptyWidgetConfigService extends AbstractBaseService
{
    /**
     * @var array конфигурации для виджета PasswordGenerateEmptyWidget
     */
    private $passwordGenerateEmptyWidgetArray = [];
    
    /**
     * Возвращает массив конфигурации для виджета PasswordGenerateEmptyWidget
     * @param $request
     * @return array
     */
    public function handle($request=null): array
    {
        try {
            if (empty($this->passwordGenerateEmptyWidgetArray)) {
                $dataArray = [];
                
                $dataArray['template'] = 'generate-empty.twig';
                
                $this->passwordGenerateEmptyWidgetArray = $dataArray;
            }
            
            return $this->passwordGenerateEmptyWidgetArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
