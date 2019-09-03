var app = new Vue({
    el: '#app',

    data: {
        products: [],
        filter: {
            keyword: '',
        },
        paginator: {
            current_page: '',
            first_page_url: '',
            from: '',
            last_page: '',
            last_page_url: '',
            next_page_url: '',
            path: '',
            per_page: '',
            prev_page_url: '',
            to: '',
            total: '',
        },
        loading: 0,
    },

    methods:{
        init() {
            this.loading = true
            this.getProducts()
        },
        getProducts(page=1) {
            let app = this
            app.loading = true
            var data = new FormData();
            data.append('keyword', app.filter.keyword);

            axios.post('/get_products?page=' + page, data)
                .then(response => {
                    app.loading = false
                    app.products = response.data.data

                    app.paginator.current_page = response.data.current_page
                    app.paginator.first_page_url = response.data.first_page_url
                    app.paginator.from = response.data.from
                    app.paginator.last_page = response.data.last_page
                    app.paginator.last_page_url = response.data.last_page_url
                    app.paginator.next_page_url = response.data.next_page_url
                    app.paginator.path = response.data.path
                    app.paginator.per_page = response.data.per_page
                    app.paginator.prev_page_url = response.data.prev_page_url
                    app.paginator.to = response.data.to
                    app.paginator.total = response.data.total

                })
                .catch(error => {
                    app.generalLoading = false
                    console.log(error);
                });
        },
        deleteProduct(id) {
            var r = confirm("Are you sure?");
            if(!r)
                return false
            app.loading = true

            axios.get('/product/delete/'+ id)
                .then(response => {
                    app.loading = false
                    if(response.data == 'success') {
                        alert('The product has been deleted successfully.')
                        $('#'+id).remove()
                    }
                    else
                        alert('Something went wrong!')
                })
                .catch(error => {
                    alert('Error occurred')
                });
        },
        editProduct(id, code, description) {
            $("#edit_form .id").val(id);
            $("#edit_form .code").val(code);
            $("#edit_form .description").val(description);
            $("#editModal").modal();
        }
        
    },

    mounted:function() {
        this.init();
        $("#app").css('opacity', 1);
        console.log('mounted')
    },
    updated: function() {
        
    },
    created: function() {
        
    }
});


