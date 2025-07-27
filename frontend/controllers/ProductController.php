<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Product;
use common\models\Category;
use yii\data\ActiveDataProvider;

class ProductController extends Controller
{
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
}
