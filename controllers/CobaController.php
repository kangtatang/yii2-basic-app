<?php

namespace app\controllers;

use app\models\uploadForm;
use app\models\EntryForm;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

use kartik\grid\GridView;

class CobaController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionImport()
    {

        echo Yii::$app->controller->action->id;
        die();

        $model = new uploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if (!$model->upload()) {
                echo "update failed";
            }
        }

        $ok = 0;
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');



            if ($file) {

                // $path = realpath(Yii::$app->basePath) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'Files' . DIRECTORY_SEPARATOR;
                $filename = "upload/Files/" . $file->name;
                $file->saveAs($filename);


                if (in_array($file->extension, array('xls', 'xlsx'))) {
                    $fileType = \PHPExcel_Iofactory::identify($filename); // the file name automatically determines the type
                    $ExcelReader = \PHPExcel_IOFactory::createReader($fileType);

                    // $phpexcel = $ExcelReader->Load(\Yii::getAlias('@webroot/upload/Files/' . $filename))->getsheet(0); // load the file and get the first sheet
                    $phpexcel = $ExcelReader->Load($filename)->getsheet(0); // load the file and get the first sheet
                    $total_Line = $phpexcel->gethighestrow(); // total number of rows
                    $total_Column = $phpexcel->gethighestcolumn(); // total number of columns

                    if (1 < $total_Line) {
                        for ($row = 2; $row <= $total_Line; $row++) {
                            $data = [];
                            for ($column = 'A'; $column <= $total_Column; $column++) {
                                $data[] = trim($phpexcel->getCell($column . $row));
                            }

                            $info = Yii::$app->db->createCommand()->insert('{{%post}}', ['title' => $data[0], 'content' => $data[1]])->execute();

                            if ($info) {
                                $ok = 1;
                            }
                        }
                    }

                    if ($ok == 1) {
                        echo "<script> alert ('import succeeded '); window.history.back ();</script>";
                    } else {
                        echo "<script> alert ('operation failed '); window.history.back ();</script>";
                    }
                }
            }
        } else {
            return $this->render('import', ['model' => $model]);
        }
    }

    public function actionEntry()
    {
        $model = new EntryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // valid data received in $model

            // do something meaningful here about $model ...

            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            // either the page is initially displayed or there is some validation error
            return $this->render('entry', ['model' => $model]);
        }
    }
}
