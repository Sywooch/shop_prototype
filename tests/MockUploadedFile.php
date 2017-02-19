<?php

namespace yii\web;

require(\Yii::getAlias('@yii/web/UploadedFile.php'));

function is_uploaded_file($filename)
{
    return file_exists($filename);
}

function move_uploaded_file($filename, $destination)
{
    return copy($filename, $destination);
}
