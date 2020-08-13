<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Order::STATUSES[$model->status ?? '-'];
                },
                'filter' => Order::STATUSES
            ],
            [
                'format' => 'raw',
                'label' => 'Products',
                'value' => function ($model) {
                    return array_reduce($model->products, function ($text, $product) {
                        $url = Html::a($product->name, 
                            ['/product/view', 'id' => $product->id]
                        );
                        $text .= $url . '<br>';
                        return $text; 
                    });
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
