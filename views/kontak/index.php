<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use app\models\Kontak;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KontakSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kontaks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kontak-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!-- <?= Html::a('Create Kontak', ['create'], ['class' => 'btn btn-success']) ?> -->
        <?= Html::button('tambah kontak', ['class' => 'btn btn-primary btn-md create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?php Pjax::begin(['id' => 'kontak']); ?>
    <!--add pjax-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'nama',
            'telepon',
            'alamat:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Kontak $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <!--add pjax-->

</div>

<?php
Modal::begin([
    'title' => '<h4>Form Kontak</h4>',
    'id' => 'modal-create',
    'size' => 'modal-md',
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>

<?php
//buat variabel untuk menampung url create kontak
$url = \yii\helpers\Url::toRoute(['/kontak/create']);
$script = <<< JS
    //tampilkan modal saat button dengan class 'create' diklik
    //tampilkan isi modal berupa view url create kontak
    $('body').on('click', '.create', function() {
       $("#modal-create").modal('show').find("#modalContent").load('$url');
    });
JS;

$this->registerJs($script);
?>