<?php

namespace app\tests\helpers;

use yii\web\UploadedFile;
use app\helpers\UploadHelper;
use app\tests\MockModel;
use app\models\ProductsModel;

/**
 * Тестирует класс app\helpers\UploadHelper
 */
class UploadHelperTests extends \PHPUnit_Framework_TestCase
{
   /**
    * Тестирует метод UploadHelper::saveImages
    */
    public function testSaveImages()
    {
        \Yii::$app->request->enableCsrfValidation = false;
        
        $temp = tempnam('/tmp', 'test');
        $handle = fopen($temp, 'w');
        fwrite($handle, file_get_contents('/var/www/html/shop/tests/source/images/2.jpg'));
        
        $temp2 = tempnam('/tmp', 'test2');
        $handle2 = fopen($temp2, 'w');
        fwrite($handle2, file_get_contents('/var/www/html/shop/tests/source/images/3.jpg'));
        
        $_POST = [
            'ProductsModel'=>[
                'code'=>'test',
                'name'=>'test',
                'description'=>'test',
                'price'=>12,
                'imagesToLoad'=>array(),
                'id_categories'=>1,
                'id_subcategory'=>1,
            ],
        ];
        
        $_FILES = [
            'ProductsModel'=>[
                'name'=>[
                    'imagesToLoad'=>['1.jpg', '2.jpg'],
                ],
                'type'=>[
                    'imagesToLoad'=>['image/jpeg', 'image/jpeg'],
                ],
                'tmp_name'=>[
                    'imagesToLoad'=>[$temp, $temp2],
                ],
                'error'=>[
                    'imagesToLoad'=>[0, 0],
                ],
                'size'=>[
                    'imagesToLoad'=>[11037, 53112],
                ],
            ],
        ];
        
        $model = new ProductsModel();
        
        $model->imagesToLoad = UploadedFile::getInstances($model, 'imagesToLoad');
        
        $_counter = 1;
        foreach ($model->imagesToLoad as $image) {
            if (!$image->saveAs('/var/www/html/shop/tests/source/images/products' . '/' . $_counter . '.' . $image->extension)) {
                echo 'ERROR!';
            }
            $_counter++;
        }
    }
}
