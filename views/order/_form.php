<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Product;
use app\models\Order;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'items')->widget(MultipleInput::className(), [
        'max' => 10,
        'columns' => [
            [
                'name'  => 'product_id',
                'type'  => 'dropDownList',
                'title' => 'Product',
                'items' => Product::find()->select('name')->indexBy('id')->column()
            ],
            [
                'name'  => 'quantity',
                'defaultValue' => 1,
                'title' => 'Quantity',
            ]
        ]
    ])->label(false); ?>

    <?php if (!$model->isNewRecord()) {
        echo $form->field($model, 'status')->dropDownList(Order::STATUSES); 
    } ?>

    <?= \yii\helpers\Html::errorSummary($model); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
