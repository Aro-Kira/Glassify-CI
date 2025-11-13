<link rel="stylesheet" href="<?php echo base_url('assets/css/general-customer/shop/2DModeling_styles.css'); ?>">

<main>

    <section class="product-2dmodeling">
        <!-- Left Side - Product Preview -->
        <div class="product-preview">
            <div class="image-slider">
                <img id="main-product-image" src="<?php echo base_url('assets/images/img-page/glass-window.jpg'); ?>"
                    alt="Window Preview" class="main-image">
                <div class="slider-controls">
                    <button class="prev">&#10094;</button>
                    <button class="next">&#10095;</button>
                </div>
                <div class="image-count">1/3</div>
            </div>

            <div class="preview-2d">
                <div class="preview-diagram">
                    <!-- 2D diagram would go here -->
                    <img src="<?php echo base_url('assets/images/img-page/2d-diagram.png'); ?>" alt="2D Window Diagram"
                        style="max-width: 100%; height: auto;">
                </div>
                <h3>2D Preview</h3>
            </div>

            <div class="upload-2d">
                <button class="upload-file" onclick="Page2DModeling.openPopup()">


                    <h3>Upload a File</h3>
                    <img src="<?php echo base_url('assets/images/img-page/attach-file.png'); ?>" alt="Upload Icon">
                </button>
            </div>

            <!-- Overlay & Popup -->
            <div class="overlay" id="uploadPopup">
                <div class="popup">
                    <!-- Header -->
                    <div class="popup-header">
                        <span>File Upload</span>
                        <button onclick="Page2DModeling.closePopup()">&times;</button>

                    </div>

                    <!-- Content -->
                    <div class="popup-content">
                        <div>
                            <div class="upload-area">
                                <img src="<?php echo base_url('assets/images/img-page/upload-file.png'); ?>"
                                    alt="Upload Icon">
                                <p><strong>Choose a file or drag & drop it here</strong></p>
                                <p>Supported file types: JPG, PNG, PDF<br>Maximum size: 25MB</p>

                                <!-- hidden input -->
                                <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
                                <!-- trigger input -->
                                <button class="browse-btn" onclick="document.getElementById('fileInput').click()">Browse
                                    Files</button>
                            </div>

                            <div class="uploaded-files">
                                <h4>Uploaded Files</h4>
                                <div class="file-placeholder" id="filePlaceholder">
                                    Uploaded files will appear here
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="popup-footer">
                        <button class="cancel-btn" onclick="closePopup()">Cancel</button>
                        <button class="done-btn">Done</button>
                    </div>
                </div>
            </div>



            <div class="estimated-price">
                <h3>Estimated Price</h3>
                <div class="price-details">
                    <table>
                        <tr>
                            <td>Shape:</td>
                            <td>Rectangle</td>
                        </tr>
                        <tr>
                            <td>Dimension:</td>
                            <td>24", 0", 18", 0"</td>
                        </tr>
                        <tr>
                            <td>Type:</td>
                            <td>Tempered</td>
                        </tr>
                        <tr>
                            <td>Thickness:</td>
                            <td>8mm</td>
                        </tr>
                        <tr>
                            <td>Edge Work:</td>
                            <td>Flat Polish</td>
                        </tr>
                        <tr>
                            <td>Frame Type:</td>
                            <td>Vinyl</td>
                        </tr>
                        <tr>
                            <td>Engraving:</td>
                            <td>N/A</td>
                        </tr>
                        <tr class="total-row">
                            <td>Total:</td>
                            <td>₱3,100.00</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="add-to-cart-container">
                <button onclick="window.location.href='add-to-cart.html'" class="add-to-cart">
                    <img src="<?php echo base_url('assets/images/img-page/shopping-cart.png'); ?>" alt="Add to Cart">
                    <h3>Add to Cart</h3>
                </button>
            </div>
        </div>

        <!-- Right Side - Product Customize -->
        <div class="product-customize">
            <div class="product-header">
                <h2>Glass & Aluminum Window</h2>
                <button class="wishlist-btn" onclick="Page2DModeling.toggleWishlist(this)">
                    <img src="<?php echo base_url('assets/images/img-page/heart.png'); ?>" alt="Wishlist"
                        class="heart-icon">
                </button>

            </div>


            <p>Start building today!</p>

            <div class="build-options">
                <button id="custom-build-btn" class="custom-build active">Customize Build</button>
                <button id="standard-btn" class="standard">Standard</button>
            </div>

            <div id="custom-build-content" class="build-content active">
                <div class="option-group">
                    <h3>Glass Shape</h3>
                    <div class="shape-buttons">
                        <button class="option-btn">Rectangle</button>
                        <button class="option-btn">Square</button>

                    </div>
                </div>

                <div class="option-group grid-controls">
                    <h3>Glass Grid</h3>
                    <div class="dimension-inputs">
                        <div class="dimension-input-group">
                            <label for="rows">Rows</label>
                            <select id="rows">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </div>
                        <div class="dimension-input-group">
                            <label for="columns">Columns</label>
                            <select id="columns">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="option-group">
                    <h3>Dimensions</h3>
                    <div class="dimension-inputs">
                        <!-- Height -->
                        <div class="dimension-input-group">
                            <label for="height">Height</label>
                            <select id="height">
                                <option selected>10"</option>
                                <option>15"</option>
                                <option>20"</option>
                                <option>25"</option>
                                <option>30"</option>
                                <option>35"</option>
                                <option>40"</option>
                                <option>45"</option>
                                <option>50"</option>
                                <option>55"</option>
                                <option>60"</option>
                                <option>65"</option>
                                <option>70"</option>
                                <option>75"</option>
                                <option>80"</option>
                                <option>85"</option>
                                <option>90"</option>
                                <option>95"</option>
                                <option>100"</option>
                            </select>
                        </div>

                        <!-- Height Fraction -->
                        <div class="dimension-input-group">
                            <label for="heightFraction">Fraction</label>
                            <select id="heightFraction" title="Height fraction" aria-label="Height fraction">
                                <option selected>0"</option>
                                <option>1/8"</option>
                                <option>1/4"</option>
                                <option>3/8"</option>
                                <option>1/2"</option>
                                <option>5/8"</option>
                                <option>3/4"</option>
                                <option>7/8"</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <div class="dimension-inputs">
                        <!-- Width -->
                        <div class="dimension-input-group">
                            <label>Width</label>
                            <select id="width" title="Width" aria-label="Width">
                                <option>10"</option>
                                <option>15"</option>
                                <option>20"</option>
                                <option>25"</option>
                                <option>30"</option>
                                <option selected>35"</option>
                                <option>40"</option>
                                <option>45"</option>
                                <option>50"</option>
                                <option>55"</option>
                                <option>60"</option>
                                <option>65"</option>
                                <option>70"</option>
                                <option>75"</option>
                                <option>80"</option>
                                <option>85"</option>
                                <option>90"</option>
                                <option>95"</option>
                                <option>100"</option>
                            </select>
                        </div>

                        <!-- Width Fraction -->
                        <div class="dimension-input-group">
                            <label for="widthFraction">Fraction</label>
                            <select id="widthFraction" title="Width fraction" aria-label="Width fraction">
                                <option selected>0"</option>
                                <option>1/8"</option>
                                <option>1/4"</option>
                                <option>3/8"</option>
                                <option>1/2"</option>
                                <option>5/8"</option>
                                <option>3/4"</option>
                                <option>7/8"</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="option-group">
                    <h3>Glass Type</h3>
                    <div class="shape-buttons">
                        <button class="option-btn">Tempered</button>
                        <button class="option-btn">Laminated</button>
                        <button class="option-btn">Double</button>
                        <button class="option-btn">Low-E</button>
                        <button class="option-btn">Tinted</button>
                        <button class="option-btn">Frosted</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Glass Thickness</h3>
                    <div class="shape-buttons">
                        <button class="option-btn">5mm</button>
                        <button class="option-btn">6mm</button>
                        <button class="option-btn">8mm</button>
                        <button class="option-btn">10mm</button>
                        <button class="option-btn">12mm</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Edge Work</h3>
                    <div class="shape-buttons">
                        <button class="option-btn">Flat Polish</button>
                        <button class="option-btn">Metered</button>
                        <button class="option-btn">Beveled</button>
                        <button class="option-btn">Seamed</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Frame Type</h3>
                    <div class="shape-buttons">
                        <button class="option-btn">Vinyl</button>
                        <button class="option-btn">Aluminum</button>
                        <button class="option-btn">Wood</button>
                    </div>
                </div>


                <div class="option-group">
                    <h3>Engraving (Optional)</h3>
                    <div class="engraving-input">
                        <input type="text" placeholder="Enter engraving text">
                    </div>
                </div>
            </div>

            <!-- Standard Build Section (Initially Hidden) -->
            <div id="standard-content" class="build-content">
                <div class="option-group">
                    <h3>Glass Size</h3>
                    <div class="size-buttons">
                        <button class="option-btn">20" x 65"</button>
                        <button class="option-btn">96" x 130"</button>
                        <button class="option-btn">100" x 144"</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Engraving (Optional)</h3>
                    <div class="engraving-input">
                        <input type="text" placeholder="Enter engraving text">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="related-products">
        <h2>Related Products</h2>
        <div class="product-grid">
            <div class="product">
                <img src="<?php echo base_url('assets/images/img-page/glass-window4.jpg'); ?>"
                    alt="900 Series Sliding Window">
                <p>900 Series Sliding Window</p>
                <button onclick="window.location.href='#'">Build and Buy</button>
            </div>
            <div class="product">
                <img src="<?php echo base_url('assets/images/img-page/glass-window5.jpg'); ?>"
                    alt="798 Series Sliding Window">
                <p>798 Series Sliding Window</p>
                <button onclick="window.location.href='#'">Build and Buy</button>
            </div>
            <div class="product">
                <img src="<?php echo base_url('assets/images/img-page/glass-window6.jpg'); ?>"
                    alt="900 Series French Type Sliding Window">
                <p>900 Series French Type Sliding Window</p>
                <button onclick="window.location.href='#'">Build and Buy</button>
            </div>
            <div class="product">
                <img src="<?php echo base_url('assets/images/img-page/glass-window7.jpg'); ?>"
                    alt="38 Series Awning Window">
                <p>38 Series Awning Window</p>
                <button onclick="window.location.href='#'">Build and Buy</button>
            </div>
        </div>

    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <h2>Customer Testimonials</h2>
        <div class="testimonial-content">
            <button class="testimonial-arrow left">
                <img src="<?php echo base_url('assets/images/img-page/testimonials-arrow.png'); ?>" alt="Previous">
            </button>
            <div class="testimonial-text">
                <p>Highly recommending this shop! Very smooth and fast transaction. Despite unfortunate events, they
                    were still able to deliver. Owner and staff are committed at great service. Exceeds expectations.
                    Will definitely be our go-to-shop for glass and aluminum.</p>
                <div class="stars">
                    <img src="<?php echo base_url('assets/images/img-page/mdi--star-circle-outline.svg'); ?>"
                        alt="ratings" class="ratings">
                    <img src="<?php echo base_url('assets/images/img-page/mdi--star-circle-outline.svg'); ?>"
                        alt="ratings" class="ratings">
                    <img src="<?php echo base_url('assets/images/img-page/mdi--star-circle-outline.svg'); ?>"
                        alt="ratings" class="ratings">
                    <img src="<?php echo base_url('assets/images/img-page/mdi--star-circle-outline.svg'); ?>"
                        alt="ratings" class="ratings">
                    <img src="<?php echo base_url('assets/images/img-page/mdi--star-circle-outline.svg'); ?>"
                        alt="ratings" class="ratings">
                </div>
                <h3 class="author">Kris-Ann Munda-Rebullana</h3>
            </div>
            <button class="testimonial-arrow right">
                <img src="<?php echo base_url('assets/images/img-page/testimonials-arrow.png'); ?>" alt="Next">
            </button>
        </div>
    </section>
</main>
<script src="<?= base_url('assets/js/2d-functions/2d_functions.js'); ?>"></script>