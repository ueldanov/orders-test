<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'price',
            'quantity',
            [
                'attribute' => 'available',
                'value' => function ($model) {
                    return $model->available === 1 ? 'Yes' : 'No';
                },
                'filter' => [1 => 'Yes', 0 => 'No']
            ],
            'size',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
