(function($) {

    $(document).ready(function () {

        $('.refresh-cart').click(function () {
            $.ajax({
                type: "POST",
                url: '/app_dev.php/cart/refresh',
                data: $('#cart').serialize(),
                success: function (data) {
                    if (data.status == 'success') {
                        window.location = '/app_dev.php/cart';
                    }
                }
            });
        });

        $('.cart-remove').click(function () {
            var productId = $(this).parent().parent().find('input').data('product');
            $.ajax({
                type: "POST",
                url: '/app_dev.php/cart/remove',
                data: 'productId=' + productId,
                success: function (data) {
                    if (data.status == 'success') {
                        window.location = '/app_dev.php/cart';
                    }
                }
            });
        });

    });

})(jQuery);