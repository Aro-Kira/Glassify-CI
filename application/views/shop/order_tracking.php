
<link rel="stylesheet" href="<?php echo base_url('assets/css/general-customer/shop/order_tracking.css'); ?>">

<div class="order-status-page">
    <?php if ($order): ?>
        <!-- Title -->
        <section class="order-header">
            <h2>Order Status</h2>
            <p>Order ID: <span><?= str_pad($order->OrderID, 14, '0', STR_PAD_LEFT) ?></span></p>
            <div class="divider"></div>
        </section>

        <!-- Order Info -->
        <section class="order-info">
            <div>
                <h4>Order ID:</h4>
                <p><?= str_pad($order->OrderID, 14, '0', STR_PAD_LEFT) ?></p>
            </div>
            <div>
                <h4>Payment Method:</h4>
                <?php 
                // Determine payment method: if ReceiptPath exists, it's E-Wallet
                $detected_payment_method = 'Cash on Delivery';
                if (isset($payment) && !empty($payment->ReceiptPath)) {
                    $detected_payment_method = 'E-Wallet';
                }
                ?>
                <p><?= $detected_payment_method ?></p>
            </div>
            <div>
                <h4>Transaction ID:</h4>
                <p>TXN<?= date('Ymd', strtotime($order->OrderDate)) . str_pad($order->OrderID, 6, '0', STR_PAD_LEFT) ?></p>
            </div>
            <div>
                <h4>Estimated Date:</h4>
                <p><?= date('F j, Y', strtotime($order->EstimatedDelivery)) ?></p>
            </div>
        </section>

        <!-- Order Progress -->
        <?php
        // Calculate progress percentage based on completed steps
        $progress_percent = 0;
        if (!empty($progress['order_placed'])) $progress_percent = 0;
        if (!empty($progress['ocular_visit'])) $progress_percent = 25;
        if (!empty($progress['in_fabrication'])) $progress_percent = 50;
        if (!empty($progress['installed'])) $progress_percent = 75;
        if (!empty($progress['completed'])) $progress_percent = 100;
        ?>
        <section class="order-progress" style="--progress-width: <?= $progress_percent ?>%;">
            <div class="step <?= $progress['order_placed'] ? 'completed' : 'pending' ?>">
                <img src="<?php echo base_url('assets/images/img-page/checkout_track.svg'); ?>" class="order-icon" alt="checkout">
                <p>Order Placed</p>
                <?php if ($progress['order_placed']): ?>
                    <span class="icon"><img src="<?php echo base_url('assets/images/img-page/check-track.png'); ?>" alt="check"></span>
                    <small><?= date('M j, Y', strtotime($order->OrderDate)) ?><br><?= date('g:i A', strtotime($order->OrderDate)) ?></small>
                <?php else: ?>
                    <span class="icon"></span>
                    <small>Pending</small>
                <?php endif; ?>
            </div>
            <div class="step <?= $progress['ocular_visit'] ? 'completed' : 'pending' ?>">
                <img src="<?php echo base_url('assets/images/img-page/ocular_track.svg'); ?>" class="order-icon" alt="ocular visit">
                <p>Ocular Visit</p>
                <?php if ($progress['ocular_visit']): ?>
                    <span class="icon"><img src="<?php echo base_url('assets/images/img-page/check-track.png'); ?>" alt="check"></span>
                    <small><?= date('M j, Y', strtotime($order->OcularDate)) ?><br>Completed</small>
                <?php else: ?>
                    <span class="icon"></span>
                    <small>Expected<br><?= date('M j, Y', strtotime($order->OcularDate)) ?></small>
                <?php endif; ?>
            </div>
            <div class="step <?= $progress['in_fabrication'] ? 'completed' : 'pending' ?>">
                <img src="<?php echo base_url('assets/images/img-page/package_track.svg'); ?>" class="order-icon" alt="fabrication">
                <p>In Fabrication</p>
                <?php if ($progress['in_fabrication']): ?>
                    <span class="icon"><img src="<?php echo base_url('assets/images/img-page/check-track.png'); ?>" alt="check"></span>
                    <small><?= date('M j, Y', strtotime($order->FabricationDate)) ?><br>Completed</small>
                <?php else: ?>
                    <span class="icon"></span>
                    <small>Expected<br><?= date('M j, Y', strtotime($order->FabricationDate)) ?></small>
                <?php endif; ?>
            </div>
            <div class="step <?= $progress['installed'] ? 'completed' : 'pending' ?>">
                <img src="<?php echo base_url('assets/images/img-page/window_track.svg'); ?>" class="order-icon" alt="Installation">
                <p>Installed</p>
                <?php if ($progress['installed']): ?>
                    <span class="icon"><img src="<?php echo base_url('assets/images/img-page/check-track.png'); ?>" alt="check"></span>
                    <small><?= date('M j, Y', strtotime($order->InstallationDate)) ?><br>Completed</small>
                <?php else: ?>
                    <span class="icon"></span>
                    <small>Expected<br><?= date('M j, Y', strtotime($order->InstallationDate)) ?></small>
                <?php endif; ?>
            </div>
            <div class="step <?= $progress['completed'] ? 'completed' : 'pending' ?>">
                <img src="<?php echo base_url('assets/images/img-page/delivered_track.svg'); ?>" class="order-icon" alt="delivery">
                <p>Completed</p>
                <?php if ($progress['completed']): ?>
                    <span class="icon"><img src="<?php echo base_url('assets/images/img-page/check-track.png'); ?>" alt="check"></span>
                    <small><?= date('M j, Y', strtotime($order->EstimatedDelivery)) ?><br>Completed</small>
                <?php else: ?>
                    <span class="icon"></span>
                    <small>Expected<br><?= date('M j, Y', strtotime($order->EstimatedDelivery)) ?></small>
                <?php endif; ?>
            </div>
        </section>

        <!-- Products Table -->
        <section class="order-products">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Customization</th>
                        <th>Price</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($order_items)): ?>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= base_url('uploads/products/' . ($item->ImageUrl ?? 'default.jpg')) ?>" alt="<?= htmlspecialchars($item->ProductName ?? 'Product') ?>" class="cart-product-img">
                                </td>
                                <td><?= htmlspecialchars($item->ProductName ?? 'Unknown Product') ?></td>
                                <td class="customization-info">
                                    <?php 
                                    // Check if item has any customization data
                                    $has_customization = !empty($item->Dimensions) || !empty($item->GlassType) || 
                                                        !empty($item->GlassThickness) || !empty($item->GlassShape) || 
                                                        !empty($item->EdgeWork) || !empty($item->FrameType) || 
                                                        !empty($item->DesignRef);
                                    ?>
                                    <?php if ($has_customization): ?>
                                        <div class="custom-layout">
                                            <?php if (!empty($item->DesignRef)): ?>
                                                <div class="design-thumbnail-wrapper">
                                                    <img src="<?= base_url($item->DesignRef) ?>" 
                                                         alt="Custom Design" 
                                                         class="design-thumbnail"
                                                         onclick="showDesignModal('<?= base_url($item->DesignRef) ?>')">
                                                    <span class="view-design-text">Click to view</span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="custom-details">
                                                <?php if (!empty($item->Dimensions)): ?>
                                                    <span class="custom-tag">Size: <?= htmlspecialchars($item->Dimensions) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item->GlassShape)): ?>
                                                    <span class="custom-tag">Shape: <?= ucfirst(htmlspecialchars($item->GlassShape)) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item->GlassType)): ?>
                                                    <span class="custom-tag">Type: <?= ucfirst(htmlspecialchars($item->GlassType)) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item->GlassThickness)): ?>
                                                    <span class="custom-tag">Thickness: <?= htmlspecialchars($item->GlassThickness) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item->EdgeWork)): ?>
                                                    <span class="custom-tag">Edge: <?= ucfirst(str_replace('-', ' ', htmlspecialchars($item->EdgeWork))) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item->FrameType)): ?>
                                                    <span class="custom-tag">Frame: <?= ucfirst(htmlspecialchars($item->FrameType)) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item->Engraving) && $item->Engraving !== 'None'): ?>
                                                    <span class="custom-tag engraving-tag">Engraving: <?= htmlspecialchars($item->Engraving) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="no-custom">Standard</span>
                                    <?php endif; ?>
                                </td>
                                <td class="price">₱<?= number_format(($item->EstimatePrice ?? 0) * ($item->Quantity ?? 1), 2) ?></td>
                                <td><?= $item->Quantity ?? 1 ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="order-summary">
            <!-- Left side (big box) -->
            <div class="summary-box">
                <h4>Order Summary</h4>
                <p>Items: <span><?= $summary['items'] ?></span></p>
                <p>Subtotal: <span>₱<?= number_format($summary['subtotal'], 2) ?></span></p>
                <p>Shipping Fee: <span>₱<?= number_format($summary['shipping'], 2) ?></span></p>
                <p>Handling Fee: <span>₱<?= number_format($summary['handling'], 2) ?></span></p>
                <h3>Total: <span>₱<?= number_format($summary['total'], 2) ?></span></h3>
            </div>

            <!-- Right side (stacked addresses) -->
            <div class="addresses">
                <div class="address-box">
                    <h4>Shipping Address</h4>
                    <?php if ($shipping_address): ?>
                        <p><b><?= htmlspecialchars($order->First_Name . ' ' . $order->Last_Name) ?></b></p>
                        <p>(+63) <?= htmlspecialchars($order->PhoneNum ?? '') ?></p>
                        <p><?= htmlspecialchars($shipping_address->AddressLine ?? '') ?>,
                            <?= htmlspecialchars($shipping_address->City ?? '') ?>,<br>
                            <?= htmlspecialchars($shipping_address->Province ?? '') ?>,
                            <?= htmlspecialchars($shipping_address->Country ?? 'Philippines') ?>
                            <?= htmlspecialchars($shipping_address->ZipCode ?? '') ?></p>
                    <?php elseif ($order->DeliveryAddress): ?>
                        <p><b><?= htmlspecialchars($order->First_Name . ' ' . $order->Last_Name) ?></b></p>
                        <p>(+63) <?= htmlspecialchars($order->PhoneNum ?? '') ?></p>
                        <p><?= htmlspecialchars($order->DeliveryAddress) ?></p>
                    <?php else: ?>
                        <p>No shipping address provided.</p>
                    <?php endif; ?>
                </div>

                <div class="address-box">
                    <h4>Billing Address</h4>
                    <?php if ($billing_address): ?>
                        <p><?= htmlspecialchars($billing_address->AddressLine ?? '') ?>,
                            <?= htmlspecialchars($billing_address->City ?? '') ?>,
                            <?= htmlspecialchars($billing_address->Province ?? '') ?></p>
                    <?php else: ?>
                        <p>Same as shipping address</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    <?php else: ?>
        <!-- No Order Found -->
        <section class="order-header">
            <h2>Order Not Found</h2>
            <p>The order you are looking for does not exist or you don't have permission to view it.</p>
            <div class="divider"></div>
            <a href="<?= base_url('list_products') ?>" class="btn-back">← Back to My Purchases</a>
        </section>
    <?php endif; ?>
</div>

<!-- Design Preview Modal -->
<div id="designModal" class="design-modal">
    <div class="design-modal-overlay" onclick="closeDesignModal()"></div>
    <div class="design-modal-content">
        <button class="design-modal-close" onclick="closeDesignModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="design-modal-header">
            <h3>Custom Design Layout</h3>
            <p>This design is included in your order</p>
        </div>
        <div class="design-modal-body">
            <img id="designModalImage" src="" alt="Custom Design">
        </div>
        <div class="design-modal-footer">
            <button class="btn-download-design" onclick="downloadDesignImage()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download Design
            </button>
        </div>
    </div>
</div>

<script>
// Design Modal Functions
function showDesignModal(imageSrc) {
    document.getElementById('designModalImage').src = imageSrc;
    document.getElementById('designModal').classList.add('active');
}

function closeDesignModal() {
    document.getElementById('designModal').classList.remove('active');
}

function downloadDesignImage() {
    const img = document.getElementById('designModalImage');
    const link = document.createElement('a');
    link.href = img.src;
    link.download = 'custom-design-' + Date.now() + '.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDesignModal();
    }
});
</script>

<script src="<?php echo base_url('js/order-status.js'); ?>"></script>
