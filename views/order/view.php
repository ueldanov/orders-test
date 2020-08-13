<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Order;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Order::STATUSES[$model->status ?? '-'];
                },
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
        ],
    ]) ?>

</div>
