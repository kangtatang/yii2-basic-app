<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kontak".
 *
 * @property int $id
 * @property string $nama
 * @property string $telepon
 * @property string $alamat
 */
class Kontak extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kontak';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'telepon', 'alamat'], 'required'],
            [['alamat'], 'string'],
            [['nama'], 'string', 'max' => 30],
            [['telepon'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'telepon' => 'Telepon',
            'alamat' => 'Alamat',
        ];
    }
}
