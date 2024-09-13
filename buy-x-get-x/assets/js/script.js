jQuery(document).ready(function ($) {
    // Arrays to hold selected product IDs and removed products
    let selectedProducts = [];
    let removeProducts = [];

    // Handle form submission
    $('#bxgx_product_form').on('submit', function (e) {
        e.preventDefault();
        console.log("form triggered");

        // Clear the selectedProducts array
        selectedProducts = [];

        // Collect all selected product IDs from the selected area
        $('.bxgx-product').each(function () {
            const productId = $(this).data('product-id');
            selectedProducts.push(productId); // Append each product ID to the selectedProducts array
        });

        console.log('Products to submit:', selectedProducts);
        console.log('Products to remove:', removeProducts);

        // Send selected and removed products via AJAX
        $.ajax({
            url: bxgxAjax.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'bxgx_save_selected_products',
                products: selectedProducts,
                remove_products: removeProducts, // Send removed products
                nonce: bxgxAjax.nonce
            },
            success: function (response) {
                alert('Products saved successfully!');
                console.log('Response from server:', response);
            },
            error: function (error) {
                console.log('Error saving products:', error);
                alert('Failed to save products.');
            }
        });
    });

    // Product search input field
    $('#bxgx_product_search').on('keyup', function () {
        const searchQuery = $(this).val();

        // AJAX request to search for products
        $.ajax({
            url: bxgxAjax.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'bxgx_search_products',
                query: searchQuery
            },
            success: function (response) {
                $('#bxgx_product_dropdown').empty().show();

                if (response.length > 0) {
                    response.forEach(function (product) {
                        $('#bxgx_product_dropdown').append(
                            '<li class="list-group-item" data-product-id="' + product.id + '">' + product.name + '</li>'
                        );
                    });
                } else {
                    $('#bxgx_product_dropdown').html('<li class="list-group-item">No products found...</li>');
                }
            },
            error: function (error) {
                console.log('Error fetching products:', error);
            }
        });
    });

    // Select a product from the dropdown
    $(document).on('click', '#bxgx_product_dropdown .list-group-item', function () {
        const productId = $(this).data('product-id');
        const productName = $(this).text();

        // Append the selected product to the selected area but do not add to the array yet
        if ($('.bxgx-product[data-product-id="' + productId + '"]').length === 0) {
            $('#selected_products').append(
                '<span class="bxgx-product" data-product-id="' + productId + '">' +
                productName + ' <a href="#" class="bxgx-remove-product">x</a></span>'
            );
        }

        $('#bxgx_product_search').val('');
        $('#bxgx_product_dropdown').empty();
    });

    // Remove a product from the selected list
    $(document).on('click', '.bxgx-remove-product', function (e) {
        e.preventDefault();

        const productId = $(this).parent().data('product-id');
        console.log('Removing product ID:', productId);

        // Add the removed product ID to removeProducts array
        if (!removeProducts.includes(productId)) {
            removeProducts.push(productId);
        }

        // Remove the product display from the frontend
        $(this).parent().remove();
    });

});
