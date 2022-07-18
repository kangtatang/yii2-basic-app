<?php

use yii\widgets\ActiveForm;

$model = new app\models\uploadForm();
$form = ActiveForm::begin([
    'id' => 'upload',
    'options' => ['enctype' => 'multipart/form-data'],
])
?>

<?= $form->field($model, 'file')->fileInput(['multiple' => 'multiple']) ?>

<button type="submit">Upload</button>
<?php ActiveForm::end() ?>