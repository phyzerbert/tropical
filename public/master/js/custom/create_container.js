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
        port_of_discharge: '',
        fetcha: '',
        total_container: '',
        peso_carga: '',
        tara: '',
        vgm: '',

    },

    methods:{
        init() {
            
        },
        getItems() {
            axios.post('/get_proforma', {id : this.proforma_id})
                .then(response => {
                    let proforma = response.data
                    this.port_of_discharge = proforma.port_of_discharge
                    this.fetcha = proforma.date
                    for (let i = 0; i < proforma.items.length; i++) {
                        const item = proforma.items[i];
                        axios.post('/get_product', {id:item.product_id})
                            .then(response1 => {
                                this.items.push({
                                    product_id: item.product_id,
                                    product_code: response1.data.code,
                                    description: response1.data.description,
                                    quantity: item.quantity,
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
        console.log(this.proforma_id)
    }
});


