<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Product;
use common\models\Order;
use common\models\OrderItem;

class CartController extends Controller
{
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $productId = Yii::$app->request->post('id');
        $quantity = Yii::$app->request->post('quantity', 1);
        $product = Product::findOne($productId);

        if ($product) {
            $cart = Yii::$app->session->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->title,
                    'price' => $product->price,
                    'quantity' => $quantity,
                ];
            }
            Yii::$app->session->set('cart', $cart);
            return ['success' => true, 'message' => 'Product added to cart.'];
        }

        return ['success' => false, 'message' => 'Product not found.'];
    }

    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $productId = Yii::$app->request->post('id');
        $quantity = Yii::$app->request->post('quantity');
        $cart = Yii::$app->session->get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                $cart[$productId]['quantity'] = $quantity;
            } else {
                unset($cart[$productId]);
            }
            Yii::$app->session->set('cart', $cart);
            return ['success' => true, 'message' => 'Cart updated.'];
        }

        return ['success' => false, 'message' => 'Product not found in cart.'];
    }

    public function actionRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $productId = Yii::$app->request->post('id');
        $cart = Yii::$app->session->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Yii::$app->session->set('cart', $cart);
            return ['success' => true, 'message' => 'Product removed from cart.'];
        }

        return ['success' => false, 'message' => 'Product not found in cart.'];
    }

    public function actionCheckout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cart = Yii::$app->session->get('cart', []);

        if (empty($cart)) {
            return ['success' => false, 'message' => 'Your cart is empty.'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order = new Order();
            $order->subtotal = 0;
            foreach ($cart as $item) {
                $order->subtotal += $item['price'] * $item['quantity'];
            }
            $order->tax = $order->subtotal * 0.10;
            $order->total = $order->subtotal + $order->tax;

            if ($order->save()) {
                foreach ($cart as $item) {
                    $product = Product::findOne($item['id']);
                    if ($product && $product->inventory >= $item['quantity']) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $item['id'];
                        $orderItem->quantity = $item['quantity'];
                        $orderItem->price = $item['price'];
                        if (!$orderItem->save()) {
                            $transaction->rollBack();
                            return ['success' => false, 'message' => 'Failed to save order item.'];
                        }
                        $product->inventory -= $item['quantity'];
                        if (!$product->save()) {
                            $transaction->rollBack();
                            return ['success' => false, 'message' => 'Failed to update product inventory.'];
                        }
                    } else {
                        $transaction->rollBack();
                        return ['success' => false, 'message' => 'Not enough inventory for ' . $item['name']];
                    }
                }
                $transaction->commit();
                Yii::$app->session->remove('cart');
                return ['success' => true, 'message' => 'Checkout successful!'];
            } else {
                $transaction->rollBack();
                return ['success' => false, 'message' => 'Failed to save order.'];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => 'An error occurred during checkout.'];
        }
    }
}
