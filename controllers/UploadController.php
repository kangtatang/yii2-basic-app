<?php

namespace app\controllers;

use app\models\uploadForm;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class UploadController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
  
    public function actionIndex(){
        echo "hello";
    }

  public function actionImport()
  {
    $model = new uploadForm();

    if (Yii::$app->request->isPost) {
      $model->file = UploadedFile::getInstance($model,'file');

      if (!$model->upload()) {
        print <<<EOT < script > alert ('upload failed ') < / script > EOT;
      }
    }

    $ok = 0;
    if ($model->load(Yii::$app->request->post())) {
      $file = UploadedFile::getInstance($model,'file');

      if ($file) {
        $filename = 'upload/Files/' . $file->name;
        $file->saveAs($filename);

        if (in_array($file->extension,array('xls','xlsx'))) {
          $fileType = \PHPExcel_ Iofactory:: identify ($file name); // the file name automatically determines the type
          $excelReader = \PHPExcel_IOFactory::createReader($fileType);

          $phpexcel = $ExcelReader->Load($filename)->getsheet (0); // load the file and get the first sheet
          $total_ Line = $phpexcel - > gethighestrow(); // total number of rows
          $total_ Column = $phpexcel - > gethighestcolumn(); // total number of columns

          if (1 < $total_line) {
            for ($row = 2;$row <= $total_line;$row++) {
              $data = [];
              for ($column = 'A';$column <= $total_column;$column++) {
                $data[] = trim($phpexcel->getCell($column.$row));
              }

              $info = Yii::$app->db->createCommand()->insert('{{%post}}',['title' => $data[0],'content' => $data[1]])->execute();

              if ($info) {
                $ok = 1;
              }
            }
          }

          if ($ok == 1) {
            Echo "< script > alert ('import succeeded '); window.history.back ();</script>";
          } else {
            Echo "< script > alert ('operation failed '); window.history.back ();</script>";
          }
        }
      }
    } else {
      return $this->render('import',['model' => $model]);
    }
  }
}