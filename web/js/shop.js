var bus = new Vue({});
var removeToCart = new Vue({});

var processEvent = {
    delimiters: ['${', '}'],
    props: ['process'],
    template: '#process-template',
};

var getItemsByCart = {
    delimiters: ['${', '}'],
    template: '#elements-cart-template',
    props: ['elements', 'total'],
    methods: {
        removeToCart: function (productId) {
            removeToCart.$emit('cart:remove', productId);
        },
        linkToProduct: function (productId) {
            url = Routing.generate('app_show_bundle_product_item', {
                id: productId
            });
            window.location = url;
        }
    }
};

var addToCart = {
    delimiters: ['${', '}'],
    template: '#add-to-cart-template',
    methods: {
        addToCart: function () {
            bus.$emit('cart:add', this.$el.querySelector('form'));
        }
    }
};

new Vue({
    delimiters: ['${', '}'],
    el: '#app',
    data: {
        process: false,
        elementsCart: [],
        totalCart: 0,
        message: '',
        messageStatus: '',
    },
    mounted: function () {
        bus.$on('cart:add', this.cartAdd.bind(this));
        removeToCart.$on('cart:remove', this.cartRemove.bind(this));
        this.getItems();
    },
    methods: {
        cartAdd: function (form) {
            var self = this;
            this.process = true;

            this.$http.post(Routing.generate('app_shop_bundle_cart_add_item'), new FormData(form)).then(function (response) {
                response.json().then(function (data) {
                    self.message = data.message;
                    self.messageStatus = data.messageStatus;

                    self.getItems().then(function () {
                        self.process = false;
                    });
                });
            });
        },
        cartRemove: function (productId) {
            var self = this;
            this.process = true;
            var formData = new FormData();
            formData.append('productId', productId);

            this.$http.post(Routing.generate('app_shop_bundle_cart_remove_item'), formData).then(function (response) {
                response.json().then(function (data) {
                    self.message = data.message;
                    self.messageStatus = data.messageStatus;

                    self.getItems().then(function () {
                        self.process = false;
                    });
                });
            });
        },
        getItems: function () {
            var self = this;

            return this.$http.get(Routing.generate('app_shop_bundle_cart_get_items')).then(function (response) {
                return response.json().then(function (data) {
                    if (data.code == 200) {
                        self.elementsCart = data.data.elements;
                        self.totalCart = data.data.total;
                    }
                });
            });
        }
    },
    components: {
        'add-to-cart': addToCart,
        'process-event': processEvent,
        'items-cart': getItemsByCart,
    }
});