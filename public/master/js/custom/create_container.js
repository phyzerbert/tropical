// Vue.directive('select', {
//     twoWay: true,
//     bind: function (el, binding, vnode) {
//         $(el).select2().on("select2:select", (e) => {
//             el.dispatchEvent(new Event('change', { target: e.target }));
//         });
//     },
//   });
var app = new Vue({
    el: '#app',

    data: {
        items: [],
        total: {
            amount: 0,
            quantity: 0
        },
        proforma_id: "",
        week_c: '',
        week_d: '',
        total_container: '',
        peso_carga: '',
        tara: '',
        vgm: '',

    },

    methods:{
        init() {
            
        },
        getItems() {
            this.items = []
            Dashmix.loader('show');
            axios.post('/get_proforma', {id : this.proforma_id})
                .then(response => {
                    let proforma = response.data
                    this.week_c = proforma.week_c
                    this.week_d = proforma.week_d
                    if(response.data.shipment){
                        let shipment_items = response.data.shipment.items
                        for (let i = 0; i < shipment_items.length; i++) {
                            const item = shipment_items[i];
                            axios.post('/get_product', {id:item.product_id})
                                .then(response1 => {
                                    this.items.push({
                                        product_id: item.product_id,
                                        product_code: response1.data.code,
                                        product_name: response1.data.name,
                                        quantity: item.quantity,
                                    })
                                })
                                .catch(error => {
                                    console.log(error);
                                });                
                        }
                        Dashmix.loader('hide');
                    }
                })
                .catch(error => {
                    console.log(error);
                }); 
        },
        calc_total_container() {
            let data = this.items
            let total_quantity = 0;
            for(let i = 0; i < data.length; i++) {
                total_quantity += parseInt(data[i].quantity)
            }
            this.total_container = total_quantity
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
        this.calc_total_container()
    }
});


