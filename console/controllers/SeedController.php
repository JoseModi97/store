<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\Category;
use common\models\Product;
use yii\helpers\FileHelper;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $this->seedCategories();
        $this->seedProducts();
    }

    private function seedCategories()
    {
        echo "Seeding categories...\n";
        $categoriesJson = file_get_contents('https://fakestoreapi.com/products/categories');
        $categories = json_decode($categoriesJson);

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->name = $categoryName;
            $category->save();
        }

        echo "Categories seeded.\n";
    }

    private function seedProducts()
    {
        echo "Seeding products...\n";
        $productsJson = file_get_contents('https://fakestoreapi.com/products');
        $products = json_decode($productsJson);

        $imagesPath = \Yii::getAlias('@frontend/web/images');
        if (!is_dir($imagesPath)) {
            FileHelper::createDirectory($imagesPath);
        }

        foreach ($products as $productData) {
            $product = new Product();
            $product->name = $productData->title;
            $product->description = $productData->description;
            $product->price = $productData->price;

            $category = Category::findOne(['name' => $productData->category]);
            if ($category) {
                $product->category_id = $category->id;
            }

            $imageUrl = $productData->image;
            $imageName = basename($imageUrl);
            $imagePath = $imagesPath . '/' . $imageName;
            file_put_contents($imagePath, file_get_contents($imageUrl));
            $product->image = '/images/' . $imageName;

            $product->save();
        }

        echo "Products seeded.\n";
    }
}
