<?php
use yii\helpers\Html;

/* @var $model common\models\Product */
?>
<div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card h-100 <?= $model->inventory <= 0 ? 'out-of-stock' : '' ?>">
        <img src="<?= Html::encode($model->image) ?>" class="card-img-top" alt="<?= Html::encode($model->title) ?>">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= Html::encode($model->title) ?></h5>
            <p class="card-text mt-auto font-weight-bold">$<?= Html::encode(number_format($model->price, 2)) ?></p>
            <div class="mt-2"> <!-- Container for stock and action buttons -->
                <p class="mb-1 stock-display">
                    <?php if ($model->inventory > 0): ?>
                        <i class="fas fa-check-circle text-success mr-1"></i><span class="text-success">In Stock: <?= $model->inventory ?></span>
                    <?php else: ?>
                        <i class="fas fa-ban text-danger mr-1"></i><span class="text-danger">Out of Stock</span>
                    <?php endif; ?>
                </p>
                <div class="d-flex mt-1 align-items-center card-action-row">
                    <?php if ($model->inventory > 0): ?>
                        <input type="number" class="form-control form-control-sm product-quantity-input" value="1" min="1" max="<?= $model->inventory ?>" style="width: 60px;" data-product-id="<?= $model->id ?>">
                        <button class="btn btn-primary btn-sm add-to-cart flex-grow-1" data-id="<?= $model->id ?>" data-name="<?= Html::encode($model->title) ?>" data-price="<?= $model->price ?>">
                            Add
                        </button>
                        <button class="btn btn-outline-secondary btn-sm view-details flex-grow-1" data-id="<?= $model->id ?>">Details</button>
                    <?php else: ?>
                        <input type="number" class="form-control form-control-sm product-quantity-input" value="1" min="1" disabled style="width: 60px;" data-product-id="<?= $model->id ?>">
                        <button class="btn btn-primary btn-sm add-to-cart flex-grow-1" data-id="<?= $model->id ?>" data-name="<?= Html::encode($model->title) ?>" data-price="<?= $model->price ?>" disabled>
                            Add
                        </button>
                        <button class="btn btn-outline-secondary btn-sm view-details flex-grow-1" data-id="<?= $model->id ?>">Details</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
