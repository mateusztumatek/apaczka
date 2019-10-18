<template>
    <div>
        <div class="d-flex justify-content-center" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <h2>Ostatnie zamówienia</h2>
        <paginate
                :page-count="10"
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
                info:{}
            }
        },
        computed:{
            reverse(){
               return this.orders.slice().reverse();
            }
        },
        mounted(){
            this.getOrders();
            this.info.page = 1;
        },
        methods:{
            changePage(page){
              this.info.page = page;
              this.getOrders();
            },
            getOrders(){
                this.isLoading = true;
                axios.get(this.base_url+'/orders_list', {params:{page: this.info.page}}).then(({data}) => {
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
