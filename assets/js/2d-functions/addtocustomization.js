$(document).on('click', '#add-to-cart-btn', function() {
    let product_id = $(this).data('product-id');

    let data = {
        product_id: product_id,
        dimensions: $('#input-height').val() + ' x ' + $('#input-width').val(),
        shape: $('.option-card[data-shape].active').data('shape'),
        type: $('.option-card[data-glass-type].active').data('glass-type'),
        thickness: $('.option-card[data-thickness].active').data('thickness'),
        edge: $('.option-card[data-edge-work].active').data('edge-work'),
        frame: $('.option-card[data-frame-type].active').data('frame-type'),
        engraving: $('#step-3 input').val() || 'None',
        price: $('#sum-total').text().replace('₱',''),
        design_ref: ''
    };

    $.ajax({
        url: base_url + "CartCon/add_customized_ajax", // unified URL
        type: "POST",
        data: data,
        success: function(response) {
            let res = JSON.parse(response);

            if(res.status === 'success') {
                // Show success notification
                alert('Added to cart! Customization ID: ' + res.customization_id);

                // Update cart counter dynamically
                if(res.cart_count !== undefined) {
                    $('#cart-count').text(res.cart_count);
                }
            } else {
                alert('Error: ' + (res.message || 'Could not add to cart'));
                console.error(res);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
        }
    });
});
