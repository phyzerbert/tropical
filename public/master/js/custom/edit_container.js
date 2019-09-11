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
        id: $("#container_id").val()
    },

    methods:{
        init() {
            axios.post('/get_container', {id : this.id})
                .then(response => {
                    let container = response.data
                    this.proforma_id = container.proforma_id
                    this.port_of_discharge = container.port_of_discharge
                    this.fetcha = container.fetcha
                    this.total_container = container.total_container
                    this.peso_carga = container.peso_carga
                    this.tara = container.tara
                    this.vgm = container.vgm
                    let product_list = JSON.parse(container.product_list)
                    for (const id in product_list) {
                        if (product_list.hasOwnProperty(id)) {
                            const element = product_list[id];
                            axios.post('/get_product', {id: id})
                            .then(response1 => {
                                this.items.push({
                                    product_id: id,
                                    product_code: response1.data.code,
                                    product_name: response1.data.name,
                                    quantity: element,
                                })
                            })
                            .catch(error => {
                                console.log(error);
                            });                             
                        }
                    }
                    
                    
                })
                .catch(error => {
                    console.log(error);
                }); 
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
                                    product_name: response1.data.name,
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


