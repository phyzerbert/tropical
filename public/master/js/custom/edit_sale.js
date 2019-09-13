var app = new Vue({
    el: '#app',

    data: {
        items: [],
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
        params: {
            id: $('#sale_id').val()
        }
    },

    methods:{
        init() {
            axios.post('/get_sale', this.params)
                .then(response => {
                    let data = response.data;
                    this.shipping_string = data.shipping_string
                    this.discount_string = data.discount_string
                    this.returns = data.returns
                    this.note = data.note

                    let items = response.data.items
                    for (let i = 0; i < items.length; i++) {
                        const item = items[i];
                        axios.post('/get_product', {id:item.product_id})
                            .then(response1 => {
                                this.items.push({
                                    product_id: item.product_id,
                                    product_name_code: response1.data.name + "(" + response1.data.code + ")",
                                    price: item.price,
                                    quantity: item.quantity,
                                    amount: item.amount,
                                    item_id: item.id,
                                })
                            })
                            .catch(error => {
                                console.log(error);
                            });                    
                    }
                })
                .catch(error => {
                    console.log(error);
                });                
        },
        add_item() {
            axios.get('/get_first_product')
                .then(response => {
                    this.items.push({
                        product_id: response.data.id,
                        product_name_code: response.data.name + "(" + response.data.code + ")",
                        price: 0,
                        quantity: 0,
                        amount: 0,
                    })
                })
                .catch(error => {
                    console.log(error);
                });            
        },
        calc_amount() {
            data = this.items
            let total_quantity = 0;
            let total_price = 0;
            for(let i = 0; i < data.length; i++) {
                this.items[i].amount = parseFloat(data[i].price) * data[i].quantity
                total_quantity += parseInt(data[i].quantity)
                total_price += data[i].amount
            }
            this.total.quantity = total_quantity
            this.total.price = total_price.toFixed(2)
        },
        calc_grand_total() {
            this.grand_total = this.total.price - this.discount - this.shipping - this.returns
        },
        calc_discount_shipping(){
            let reg_patt1 = /^\d+(?:\.\d+)?%$/
            let reg_patt2 = /^\d+$/
            if(reg_patt1.test(this.discount_string)){
                this.discount = this.total.price*parseFloat(this.discount_string)/100
                // console.log(this.discount)
            }else if(reg_patt2.test(this.discount_string)){
                this.discount = this.discount_string
            }else if(this.discount_string == ''){
                this.discount = 0
            }else {
                this.discount_string = '0';
            }

            if(reg_patt1.test(this.shipping_string)){
                this.shipping = this.total.price*parseFloat(this.shipping_string)/100
                // console.log("percent")
            }else if(reg_patt2.test(this.shipping_string)){
                this.shipping = this.shipping_string
            }else if(this.shipping_string == ''){
                this.shipping = 0
            }else {
                this.shipping_string = '0';
            }

        },
        remove(i) {
            this.items.splice(i, 1)
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
        number(value) {
            let val = value;
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    },

    mounted:function() {
        this.init();
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
                app.items[index].product_id = ui.item.id
                app.items[index].product_code = ui.item.label
                app.items[index].price = 0
                app.items[index].quantity = 0
                app.items[index].amount = 0
            }
        });
    }    
});


