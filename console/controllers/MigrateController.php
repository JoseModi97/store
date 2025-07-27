<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\Category;
use common\models\Product;
use yii\helpers\Console;

class MigrateController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Fetching data from fakestoreapi.com...\n", Console::FG_YELLOW);
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://fakestoreapi.com/products');
        $productsData = json_decode($res->getBody(), true);

        if ($productsData) {
            $this->stdout("Data fetched successfully. Migrating data...\n", Console::FG_GREEN);
            foreach ($productsData as $productData) {
                $category = Category::findOne(['name' => $productData['category']]);
                if (!$category) {
                    $category = new Category();
                    $category->name = $productData['category'];
                    $category->save();
                }

                $product = new Product();
                $product->title = $productData['title'];
                $product->description = $productData['description'];
                $product->price = $productData['price'];
                $product->image = $productData['image'];
                $product->category_id = $category->id;
                $product->inventory = rand(10, 100);
                $product->save();
            }
            $this->stdout("Data migrated successfully.\n", Console::FG_GREEN);
        } else {
            $this->stdout("Failed to fetch data.\n", Console::FG_RED);
        }
    }
}
