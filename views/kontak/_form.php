<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Kontak */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kontak-form">
    comp
    <?php $form = ActiveForm::begin([
        'id' => 'add-form' //tambahkan id
    ]); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telepon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS
    $('#add-form').on('beforeSubmit', function(e) {
        var form = $(this);
        //siapkan data form input untuk dipost via ajax
        var formData = form.serialize();
        //submit form via ajax
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                //ketika submit berhasil,modal dihidden 
                $("#modal-create").modal('hide');
                //lalu refreh gridview dg idpjax=kontak
                $.pjax({container: '#kontak'});               
            },
            error: function () {
                alert("Something went wrong");
            }
        });

    }).on('submit', function(e){
       //ini mencegah halaman redirect ke url action pada form
        e.preventDefault();
   });
JS;

$this->registerJs($script);
?>