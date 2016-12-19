<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы писка
 */
class SearchForm extends AbstractBaseForm
{
    /**
     * Сценарий писка данных
     */
    const GET = 'get';
    
    /**
     * @var string искомая фраза
     */
    public $search;
    /**
     * @var string URL, с которого был запрошен поиск
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::GET=>['search', 'url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['search', 'url'], 'required', 'on'=>self::GET],
        ];
    }
}
