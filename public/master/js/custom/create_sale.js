var app = new Vue({
    el: '#app',

    data: {
        order_items: [],
        products: [],
        selected_product: '',
        total: {
            quantity: 0,
            price: 0
        },
        discount: 0,
        discount_string: 0,
        shipping: '0',
        shipping_string: '0',
        returns: 0,
        grand_total: 0,
    },

    methods:{
        init() {
            // axios.get('/get_products')
            //     .then(response => {
            //         this.products = response.data;
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     });
        },
        add_item() {
            axios.get('/get_first_product')
                .then(response => {
                    this.order_items.push({
                        product_id: response.data.id,
                        product_name_code: response.data.name + "(" + response.data.code + ")",
                        price: 0,
                        quantity: 1,
                        amount: 0,
                    })
                    Vue.nextTick(function() {
                        app.$refs['product'][app.$refs['product'].length - 1].select()
                    });
                })
                .catch(error => {
                    console.log(error);
                });            
        },
        calc_amount() {
            data = this.order_items
            let total_quantity = 0;
            let total_price = 0;
            for(let i = 0; i < data.length; i++) {
                this.order_items[i].amount = parseFloat(data[i].price) * data[i].quantity
                total_quantity += parseFloat(data[i].quantity)
                total_price += data[i].amount
            }
            this.total.quantity = total_quantity.toFixed(2)
            this.total.price = total_price
        },
        calc_grand_total() {
            this.grand_total = this.total.price - this.discount - this.shipping - this.returns
        },
        calc_discount_shipping(){
            let reg_patt1 = /^\d+(?:\.\d+)?%$/
            let reg_patt2 = /^\d+$/
            if(reg_patt1.test(this.discount_string)){
                this.discount = this.total.price*parseFloat(this.discount_string)/100
            }else if(reg_patt2.test(this.discount_string)){
                this.discount = this.discount_string
            }else if(this.discount_string == ''){
                this.discount = 0
            }else {
                this.discount_string = '0';
            }

            if(reg_patt1.test(this.shipping_string)){
                this.shipping = this.total.price*parseFloat(this.shipping_string)/100
            }else if(reg_patt2.test(this.shipping_string)){
                this.shipping = this.shipping_string
            }else if(this.shipping_string == ''){
                this.shipping = 0
            }else {
                this.shipping_string = '0';
            }

        },
        remove(i) {
            this.order_items.splice(i, 1)
        }
    },
    filters:{
        currency: function (value) {
            var digitsRE = /(\d{3})(?=\d)/g
            value = parseFloat(value)
            if (!isFinite(value) || (!value && value !== 0)) return ''
            var stringified = Math.abs(value).toFixed(2)
            var _int = stringified.slice(0, -3)
            var i = _int.length % 3
            var head = i > 0
              ? (_int.slice(0, i) + (_int.length > 3 ? ',' : ''))
              : ''
            var _float = stringified.slice(-3)
            var sign = value < 0 ? '-' : ''
            return sign + head +
              _int.slice(i).replace(digitsRE, '$1,') +
              _float
        },
        formatPrice(value) {
            let val = value;
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    },

    mounted:function() {
        this.init();
        this.add_item()
        $("#app").css('opacity', 1);
    },
    created: function() {
        var self = this
        $(document).keydown(function(e){
            if(e.keyCode == 21 || e.keyCode == 17 || e.keyCode == 25){
                self.add_item()
            }
        });
    },
    updated: function() {
        this.calc_amount()
        this.calc_discount_shipping()
        this.calc_grand_total()
        $(".product").autocomplete({
            source : function( request, response ) {
                axios.post('/get_autocomplete_products', { keyword : request.term })
                    .then(resp => {
                        // response(resp.data);
                        response(
                            $.map(resp.data, function(item) {
                                return {
                                    label: item.name + "(" + item.code + ")",
                                    value: item.name + "(" + item.code + ")",
                                    id: item.id,
                                }
                            })
                        );
                    })
                    .catch(error => {
                        console.log(error);
                    }
                );
            }, 
            minLength: 1,
            select: function( event, ui ) {
                let index = $(".product").index($(this));
                app.order_items[index].product_id = ui.item.id
                app.order_items[index].product_name_code = ui.item.label
                app.order_items[index].price = 0
                app.order_items[index].quantity = 1
                app.order_items[index].amount = 0
            }
        });
    }    
});


