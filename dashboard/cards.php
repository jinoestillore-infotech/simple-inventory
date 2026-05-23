<div class="row g-4 justify-content-center">

        <div class="col-12 col-md-4">
            <a href="../items/index.php" class="text-decoration-none text-dark">
                <div class="card dashboard-card text-center shadow-sm">
                    <div class="card-body">
                        <p class="fs-3 m-0"><?php echo $item_total; ?></p>
                        <i class="bi bi-box-seam text-primary mb-3"></i>
                        <h5 class="card-title">Items</h5>
                        <p class="card-text m-0">Manage all inventory items.</p>
                        <i class="bi bi-box-arrow-in-right" style="font-size: 30px;"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="../supplier/index.php" class="text-decoration-none text-dark">
                <div class="card dashboard-card text-center shadow-sm">
                    <div class="card-body">
                        <p class="fs-3 m-0"><?php echo $supplier_total; ?></p>
                        <i class="bi bi-truck text-success mb-3"></i>
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text m-0">Manage suppliers and contacts.</p>
                        <i class="bi bi-box-arrow-in-right" style="font-size: 30px;"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="../category/index.php" class="text-decoration-none text-dark">
                <div class="card dashboard-card text-center shadow-sm">
                    <div class="card-body">
                        <p class="fs-3 m-0"><?php echo $category_total; ?></p>
                        <i class="bi bi-tags text-warning mb-3"></i>
                        <h5 class="card-title">Categories</h5>
                        <p class="card-text m-0">Manage item categories.</p>
                        <i class="bi bi-box-arrow-in-right" style="font-size: 30px;"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
