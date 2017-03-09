var addToCart = {
    delimiters: ['${', '}'],
    template: '#add-to-cart-template',
    methods: {
        addToCart: function (event) {
            if (event) event.preventDefault();

            var form = $(this.$el).closest('form')[0];
            console.log(form);
            this.$http.post(Routing.generate('app_shop_bundle_cart_add_item'), new FormData(form));
        }
    }
};

new Vue({
    delimiters: ['${', '}'],
    el: '#app',
    components: {
        'add-to-cart': addToCart
    }
});