<?php

namespace console\controllers;

use yii\console\Controller;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $this->seedProducts();
        $this->seedCategories();
    }

    private function seedCategories()
    {
        echo "Seeding categories...\n";
        $categories = file_get_contents('https://fakestoreapi.com/products/categories');
        $categories = json_decode($categories);

        foreach ($categories as $category) {
            $model = new \common\models\Category();
            $model->name = $category;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
        }

        echo "Categories seeded.\n";
    }

    private function seedProducts()
    {
        echo "Seeding products...\n";
        $products = file_get_contents('https://fakestoreapi.com/products');
        $products = json_decode($products);

        foreach ($products as $product) {
            $model = new \common\models\Product();
            $model->title = $product->title;
            $model->price = $product->price;
            $model->description = $product->description;
            $model->category_id = $this->getCategoryId($product->category);
            $model->image = $this->saveImage($product->image);
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save();
        }

        echo "Products seeded.\n";
    }

    private function getCategoryId($categoryName)
    {
        $category = \common\models\Category::find()->where(['name' => $categoryName])->one();
        return $category ? $category->id : null;
    }

    private function saveImage($imageUrl)
    {
        $imageContent = file_get_contents($imageUrl);
        $imageName = basename($imageUrl);
        $imagePath = \Yii::getAlias('@frontend/web/images/') . $imageName;
        file_put_contents($imagePath, $imageContent);
        return '/images/' . $imageName;
    }
}
