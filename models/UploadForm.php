<?php

namespace app\models;

use yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class uploadForm extends ActiveRecord
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'upload file '
        ];
    }

    public function upload()
    {
        $file = UploadedFile::getInstance($this, 'file');

        if ($this->rules()) {
            $tmp_file = $file->baseName . '.' . $file->extension;
            $path = 'upload/' . 'Files/';
            if (is_dir($path)) {
                $file->saveAs($path . $tmp_file);
            } else {
                mkdir($path, 0777, true);
            }
            $file->saveAs($path . $tmp_file);
            return true;
        } else {
            return 'validation failed';
        }
    }
}
