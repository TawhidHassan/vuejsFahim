<template>
    <div id="app">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">customer
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem;">
                            <button type="button" class="btn btn-primary" @click="reload">Reload <i class="fas fa-sync"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row">
                             <div class="col-md-2">
                                 <strong>search By: </strong>
                             </div>
                             <div class="col-md-3">
                                 <select class="form-control" v-model="queryFiled" id="fileds">
                                     <option value="name">name</option>
                                     <option value="email">email</option>
                                     <option value="address">address</option>
                                     <option value="phone">phone</option>
                                 </select>
                             </div>
                             <div class="col-md-7">
                                 <input type="text" v-model="query" class="form-control" placeholder="search">
                             </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Total</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-show="customers.length" v-for="(customer, index) in customers" :key="customer.id">
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ customer.name}}</td>
                                    <td>{{ customer.email }}</td>
                                    <td>{{ customer.phone }}</td>
                                    <td>{{ customer.total }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-show="!customers.length">
                                    <td colspan="6">
                                        <div class="alert alert-danger" role="alert">
                                            Sorry :( No data found.
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <pagination-component v-if="pagination.last_page > 1"
                              :pagination="pagination"
                              :offset="5"
                              @paginate="query === '' ? getData() : searchData()"
        ></pagination-component>
        <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
    import PaginationComponent from "./partial/PaginationComponent";
    export default {
        components: {PaginationComponent},
        data(){
          return{
              customers: [],
              query:'',
              queryFiled:'name',
              pagination:{
                  current_page:1,
              }
          }
        },
        watch:{
          query:function(newQ,old){
              if (newQ===''){
                  this.getData();
              }
              else {
                  this.searchData()
              }
          }
        },
        methods:{
            getData(){
                this.$Progress.start()
                axios.get('api/customer?page='+this.pagination.current_page)
                    .then(response => {
                        this.customers = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish()
                    })
                    .catch(e => {
                        console.log(e)
                        this.$Progress.fail()
                    })

          },
            searchData(){
                this.$Progress.start()
                axios.get('api/search/customer/'+this.queryFiled+'/'+this.query+'?page='+this.current_page)
                    .then(response=>{
                        this.customers=response.data.data
                        this.pagination=response.data.meta
                        this.$Progress.finish()
                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
            },
            reload(){
                this.getData()
                this.query = ''
                this.queryFiled = 'name'
                this.$snotify.success('Data Successfully Refresh','Success')
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>
