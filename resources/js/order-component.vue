<template>
    <div>
        <div class="d-flex justify-content-center" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Wyszukaj po ID</label>
                <input type="number" class="form-control" v-model="params.id" @change="getOrders()">
            </div>
        </div>
        <h2>Ostatnie zamówienia</h2>
       <!-- <div>
            <p>filtruj:</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" v-model="params.status" @change="getOrders()">
                            <option :value="0">Nowe</option>
                            <option :value="1">W trakcie realizacji</option>
                            <option :value="2">Zrealizowane</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>-->
        <paginate
                :page-count="info.last_page"
                :click-handler="changePage"
                :prev-text="'Prev'"
                :next-text="'Next'"
                prev-class="page-item"
                prev-link-class="page-link"
                next-link-class="page-link"
                next-class="page-item"
                :container-class="'pagination'"
                :page-class="'page-item'"
                :page-link-class="'page-link'">
        </paginate>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Imię nazwisko</th>
                <th scope="col">Status</th>
                <th scope="col">Typ wysyłki</th>
                <th scope="col">Adres wysyłki</th>
                <th scope="col">Utworzone</th>
                <th scope="col">Wysłane</th>
                <th scope="col" class="col-auto">Akcje</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="order in reverse" :key="order.id">
                    <th scope="row">{{order.id}}</th>
                    <td>{{order.address.name}}</td>
                    <td>{{getStatus(order)}}</td>
                    <td>{{order.shipping_relation.name}}</td>
                    <td>{{order.address.city}}</td>
                    <td>{{order.creation_date}}</td>
                    <td><span v-if="order.order_info && order.order_info.is_send"><div class="alert alert-info p-1">Tak</div></span><span v-else><div class="alert alert-danger p-1">Nie</div></span></td>
                    <td><a :href="base_url+'/orders?order_id='+order.id" class="btn btn-primary" v-if="order.order_info && order.order_info.is_send">Wyślij ponownie</a> <a :href="base_url+'/orders?order_id='+order.id" class="btn btn-primary" v-else>Wyślij</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    import Paginate from 'vuejs-paginate'
    export default {
        components:{
            Paginate
        },
        data(){
            return{
                orders: [],
                params:{},
                info:{
                    last_page: 1
                }
            }
        },
        computed:{
            reverse(){
               return this.orders.slice().reverse();
            }
        },
        mounted(){
            this.info.page = 1;
            this.getOrders();
        },
        methods:{
            changePage(page){
              this.info.page = page;
              this.getOrders();
            },
            getOrders(){
                var obj1={page: this.info.page};
                var obj = {...obj1, ...this.params};
                this.isLoading = true;
                axios.get(this.base_url+'/orders_list', {params:obj}).then(({data}) => {
                    this.orders = data.data;
                    this.info.page = data.current_page;
                    this.info.last_page = data.last_page;
                    this.info.total = data.total;
                    this.isLoading = false;
                }).catch(e => {

                })
            },
            getStatus(order){
                switch (order.status) {
                    case 0:
                        return 'Nowe';
                        break;
                    case 1:
                        return 'W realizacji';
                        break;
                    case 2:
                        return 'Zrealizowane';
                        break;
                }
            }
        }
    }
</script>
<style lang="scss">
    .list-enter-active, .list-leave-active {
        transition: all 500ms;
    }
    .list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
        opacity: 0;
        transform: translateX(-30px);
    }
</style>
