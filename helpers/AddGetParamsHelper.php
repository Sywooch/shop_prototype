<?php

namespace app\helpers;

/**
 * Предоставляет методы для добавления, изменения, удаления из URL GET параметров
 */
class AddGetParamsHelper
{
    private static $_newUrl;
    
    /**
     * Добавялет GET параметры, изменяет на текущие
     * @param string $currentUrl текущий URL к которому необходимо применить изменения
     * @param array $params параметры в формате [key=>val]
     * @return string отредактированный URL
     */
    public static function addParam($currentUrl, Array $params)
    {
        self::$_newUrl = $currentUrl;
        
        foreach ($params as $key=>$val)
        {
            if (strpos(self::$_newUrl, $key . '=')) {
                self::$_newUrl =  preg_replace('/' . $key . '=[^? &]+/', $key . '=' . $val, self::$_newUrl);
            } else {
                if (strpos(self::$_newUrl, '?')) {
                    self::$_newUrl .= '&';
                } else {
                    self::$_newUrl .= '?';
                }
                self::$_newUrl .= $key . '=' . $val;
            }
        }
        return self::$_newUrl;
    }
    
    /**
     * Удаляет GET параметры из строки URL
     * @param string $currentUrl текущий URL к которому необходимо применить изменения
     * @param string $param параметр, которые необходимо убрать из URL
     * @return string отредактированный URL
     */
    public static function delParam($currentUrl, Array $params)
    {
        self::$_newUrl = $currentUrl;
        
        foreach ($params as $param) {
            if (strpos(self::$_newUrl, $param . '=')) {
                self::$_newUrl =  preg_replace(['/\?{1}' . $param . '=[^? &]+$/', '/&{1}' . $param . '=[a-z A-Z 0-9 .]+/', '/' . $param . '=[a-z A-Z 0-9 .]+&{1}/'], '', self::$_newUrl);
            }
        }
        return self::$_newUrl;
    }
}
