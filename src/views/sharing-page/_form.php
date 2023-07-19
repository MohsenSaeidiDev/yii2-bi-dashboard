<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sadi01\dateRangePicker\dateRangePicker;

/** @var yii\web\View $this */
/** @var app\models\SharingPage $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sharing-page-form">

    <?php $form = ActiveForm::begin(['id' => 'sharing-form', 'enableClientValidation' => true]); ?>
    <?= $form->field($model, 'page_id')->dropDownList(
        ArrayHelper::map(\sadi01\bidashboard\models\ReportPage::find()->all(), 'id', 'title'),
        ['prompt' => 'Select a page']
    ) ?>

    <?= $form->field($model, 'expire_time')->widget(dateRangePicker::class,[
        'options'  => [
            'drops' => 'down',
            'placement' => 'right',
            'opens' => 'left',
            'language' => 'fa',
            'jalaali'=> true,
            'showDropdowns'=> true,
            'singleDatePicker' => true,
            'useTimestamp' => true,
            'timePicker' => true,
            'timePicker24Hour' => true,
            'timePickerSeconds' => true,
            'locale'=> [
                'format'=> 'jYYYY/jMM/jDD',

            ],
        ],
        'htmlOptions' => [
            'class'	=> 'form-control',
            'id' => 'from_date_time',
            'autocomplete' => 'off',
        ]
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
