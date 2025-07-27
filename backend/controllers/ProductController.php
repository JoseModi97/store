<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Product;
use yii\filters\AccessControl;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
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
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN;
                        }
                    ],
                ],
            ],
        ];
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

    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $model->image = '/uploads/' . $model->imageFile->baseName . '.' . $model->imageFile->extension;
            }

            if ($model->save()) {
                if ($model->imageFile) {
                    $model->imageFile->saveAs(Yii::getAlias('@frontend/web/uploads/') . $model->imageFile->baseName . '.' . $model->imageFile->extension);
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile) {
                $model->image = '/uploads/' . $model->imageFile->baseName . '.' . $model->imageFile->extension;
            }

            if ($model->save()) {
                if ($model->imageFile) {
                    $model->imageFile->saveAs(Yii::getAlias('@frontend/web/uploads/') . $model->imageFile->baseName . '.' . $model->imageFile->extension);
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
