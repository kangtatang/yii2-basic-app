<?php

namespace app\modules\blog\models;

use Yii;

use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 */
class Post extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx'],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'file' => 'upload file ',
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
