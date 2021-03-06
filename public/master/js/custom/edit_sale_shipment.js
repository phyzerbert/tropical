var app = new Vue({
    el: '#app',

    data: {
        items: [],
        products: [],
        selected_product: '',
        total: {
            amount: 0,
            quantity: 0
        },
        keyword: '',
        total_to_pay: 0,
        params: {
            id: $('#invoice_id').val()
        }
    },

    methods:{
        init() {
            axios.post('/get_sale_shipment', this.params)
                .then(response => {
                    let invoice = response.data                    
                    this.vat = invoice.vat_amount
                    for (let i = 0; i < invoice.items.length; i++) {
                        const item = invoice.items[i];
                        this.items.push({
                            product_id: item.product_id,
                            product_code: item.product.name + "(" + item.product.code + ")",
                            product_name: item.product.name,
                            price: item.price,
                            quantity: item.quantity,
                            amount: item.amount,
                            surcharge_reduction: item.surcharge_reduction,
                            total_amount: item.total_amount,
                            item_id: item.id
                        })                
                    }
                })
                .catch(error => {
                    console.log(error);
                }); 
        },
        add_item() {
            // let app = this
            axios.get('/get_first_product')
                .then(response => {
                    this.items.push({
                        product_id: response.data.id,
                        product_code: response.data.code,
                        product_name: response.data.name,
                        price: 0,
                        quantity: 1,
                        total_amount: 0,
                    })
                    Vue.nextTick(function() {
                        app.$refs['product'][app.$refs['product'].length - 1].select()
                    });
                })
                .catch(error => {
                    console.log(error);
                });            
        },
        calc_subtotal() {
            let data = this.items
            let total_quantity = 0;
            let total_amount = 0;

            for(let i = 0; i < data.length; i++) {
                this.items[i].total_amount = (parseFloat(data[i].price) * data[i].quantity).toFixed(2)
                total_quantity += parseInt(data[i].quantity)
                total_amount += parseFloat(data[i].total_amount)
            }
            this.total.quantity = total_quantity
            this.total.amount = total_amount
        },
        calc_total_to_pay() {
            this.total_to_pay = this.total.amount
        },
        remove(i) {
            this.items.splice(i, 1)
        },
        formatPrice(value) {
            let val = value;
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        searchProduct() {
            
        }
    },

    mounted:function() {
        this.init();
        $("#app").css('opacity', 1);
    },
    updated: function() {
        this.calc_subtotal()
        this.calc_total_to_pay()
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
                                    name: item.name,
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
                app.items[index].product_name = ui.item.name
                app.items[index].price = 0
                app.items[index].quantity = 1
                app.items[index].total_amount = 0
            }
        });
    },
    created: function() {
        var self = this
        // $(document).keydown(function(e){
        //     if(e.keyCode == 21 || e.keyCode == 17 || e.keyCode == 25){
        //         self.add_item()
        //     }else if(e.keyCode == 16){
        //         if($("#addProductModal").hasClass("show")){
        //             $("#addProductModal").modal('hide');
        //         } else {
        //             $("#addProductModal").modal();
        //         }                
        //     }
        // });
    }
});


