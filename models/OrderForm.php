<?php

namespace app\models;

/**
 * This is the form class for "order".
 */
class OrderForm extends \yii\base\Model 
{
    /**
     * @var array
     */
    public $items;
    /**
     * @var integer
     */
    public $status = 1;
    /**
     * @var \app\models\Order
     */
    private $model;

    /**
     * @param \app\models\Order $model
     * @param array $config
     */
    public function __construct($model, $config = [])
    {
        $this->model = $model;
        $this->status = $model->status;
        if ($model !== null && $model->orderProducts !== null) {
            foreach ($model->orderProducts as $orderProduct) {
                $this->items[] = [
                    'product_id' => (string)$orderProduct->product_id, 
                    'quantity' => (string)$orderProduct->quantity
                ];
            }
        }
        parent::__construct($config);
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['items', 'filter', 'filter' => function($items) {
                $temp = [];
                foreach ($items as $item) {
                    $quantity = $temp[$item['product_id']]['quantity'] ?? 0;
                    $temp[$item['product_id']] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $quantity + $item['quantity'],
                    ];
                }
                return array_values($temp);
            }],
            [['status'], 'integer'],
            [['items'], 'validateProducts'],
            [['items'], 'required'],
        ];
    }

    /**
     * Validates given products
     * 
     * @return void
     */
    public function validateProducts($attribute, $params, $validator)
    {
        foreach ($this->items as $index => $item) {
            $product = Product::findOne($item['product_id']);
            if ($item['quantity'] > $product->quantity) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, "Not enough quantity of \"{$product->name}\". Available: {$product->quantity}");
            }
        }
    }

    /**
     * Save to database
     * 
     * @return boolean
     */
    public function save() 
    {
        $model = $this->model;
        $model->status = $this->status;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model->unlinkAll('orderProducts', true);
            if ($this->validate() && $model->save()) {
                foreach ($this->items as $item) {
                    $product = Product::findOne($item['product_id']);
                    if ($product) {
                        $model->link('products', $product, ['quantity' => $item['quantity'] ?? null]);
                    }
                }
                $transaction->commit();
                return true;
            }
        } catch (\yii\db\Exception $e) {
            $this->addError('items', 'Database error. Try again');
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Checks if model is newly created
     * 
     * @return boolean
     */
    public function isNewRecord()
    {
        return $this->model->isNewRecord ?? true;
    }
}