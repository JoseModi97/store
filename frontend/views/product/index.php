<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $productsDataProvider yii\data\ActiveDataProvider */
/* @var $categories \common\models\Category[] */

$this->title = 'POS System';
?>
<div class="site-index">

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Product Catalog -->
            <div class="col-12 col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Products</h2>
                    <div id="search-container" style="flex-grow: 0.4;">
                        <input type="text" id="search-input" class="form-control" placeholder="Search products by name or description...">
                    </div>
                </div>
                <div id="category-filters" class="mb-3 d-flex flex-wrap">
                    <button class="btn btn-outline-secondary active category-filter-btn m-1" data-category="all">All Categories</button>
                    <?php foreach ($categories as $category): ?>
                        <button class="btn btn-outline-secondary category-filter-btn m-1" data-category="<?= $category->id ?>"><?= Html::encode($category->name) ?></button>
                    <?php endforeach; ?>
                </div>
                <div id="product-grid" class="row">
                    <?= ListView::widget([
                        'dataProvider' => $productsDataProvider,
                        'itemView' => '_product_item',
                        'summary' => '',
                        'options' => [
                            'tag' => false,
                        ],
                        'itemOptions' => [
                            'tag' => false,
                        ],
                    ]) ?>
                </div>
            </div>

            <!-- Cart Panel (Visible on LG screens and up) -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sticky-top" style="top: 20px;">
                    <h2>Cart</h2>
                    <div id="cart-items-sidebar" class="list-group mb-3">
                        <p class="text-muted text-center m-3" id="cart-empty-message-sidebar">Your cart is empty.</p>
                    </div>

                    <!-- Totals Area -->
                    <div id="totals-area-sidebar">
                        <h4>Totals</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Subtotal
                                <span id="subtotal-sidebar">$0.00</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tax (10%)
                                <span id="tax-sidebar">$0.00</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Total
                                <span id="total-sidebar">$0.00</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-3">
                        <button id="checkout-button-sidebar" class="btn btn-success btn-block">Checkout</button>
                        <button id="clear-cart-button-sidebar" class="btn btn-outline-danger btn-block mt-2">Clear Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Modal (for small screens) -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="cart-items-modal" class="list-group mb-3">
                        {/* Cart items will be inserted here by app.js */}
                        <p class="text-muted text-center m-3" id="cart-empty-message-modal">Your cart is empty.</p>
                    </div>
                    <!-- Totals Area in Modal -->
                    <div id="totals-area-modal">
                        <h4>Totals</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Subtotal
                                <span id="subtotal-modal">$0.00</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tax (10%)
                                <span id="tax-modal">$0.00</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                Total
                                <span id="total-modal">$0.00</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="clear-cart-button-modal">Clear Cart</button>
                    <button type="button" class="btn btn-success" id="checkout-button-modal">Checkout</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailModalLabel">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="" id="modalProductImage" class="img-fluid mb-3" alt="Product Image" style="max-height: 400px; object-fit: contain; width: 100%;">
                        </div>
                        <div class="col-md-6">
                            <h4 id="modalProductName"></h4>
                            <p><strong >Category:</strong> <span id="modalProductCategory"></span></p>
                            <p><strong>Price:</strong> <span id="modalProductPrice" class="font-weight-bold"></span></p>
                            <p><strong>Availability:</strong> <span id="modalProductStock"></span></p>
                            <p id="modalProductDescription"></p>
                            <!-- Optional: Add to cart from modal -->
                            <div class="form-group d-flex align-items-center mt-3" id="modal-add-to-cart-controls" style="display:none;">
                                <label for="modalProductQuantity" class="mr-2">Qty:</label>
                                <input type="number" id="modalProductQuantity" class="form-control form-control-sm mr-2" value="1" min="1" style="width: 70px;">
                                <button id="modalAddToCartButton" class="btn btn-primary btn-sm">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
$this->registerJsFile('@web/js/app.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
