<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Product;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use Yii;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $productsDataProvider = new ActiveDataProvider([
            'query' => Product::find(),
        ]);
        $categories = Category::find()->all();

        return $this->render('index', [
            'productsDataProvider' => $productsDataProvider,
            'categories' => $categories,
        ]);
    }

    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetProducts()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $products = Product::find()
            ->select('products.*, categories.name as category_name')
            ->leftJoin('categories', 'products.category_id = categories.id')
            ->asArray()
            ->all();
        return $products;
    }

    public function actionGetCategories()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $categories = Category::find()->asArray()->all();
        return $categories;
    }
}
