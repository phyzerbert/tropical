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
        vat: 0,
        total_to_pay: 0,
    },

    methods:{
        init() {
            this.add_item()
        },
        add_item() {
            // let app = this
            axios.get('/get_first_product')
                .then(response => {
                    this.items.push({
                        product_id: response.data.id,
                        product_code: response.data.name + "(" + response.data.code + ")",
                        product_name: response.data.name,
                        price: 0,
                        quantity: 1,
                        amount: 0,
                        surcharge_reduction: 0,
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
                this.items[i].amount = (parseFloat(data[i].price) * data[i].quantity).toFixed(2)
                this.items[i].total_amount = parseInt(data[i].amount) + parseInt(data[i].surcharge_reduction)
                total_quantity += parseInt(data[i].quantity)
                total_amount += parseFloat(data[i].total_amount)
            }
            this.total.quantity = total_quantity
            this.total.amount = total_amount
        },
        calc_total_to_pay() {
            this.total_to_pay = this.total.amount - this.vat
        },
        remove(i) {
            this.items.splice(i, 1)
        },
        formatPrice(value) {
            let val = value;
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
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
                app.items[index].amount = 0
                app.items[index].surcharge_reduction = 0
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


