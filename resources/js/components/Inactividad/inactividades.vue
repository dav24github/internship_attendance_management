<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Lista de Inactividades</h4>
                            </div>
                            <div>
                                <input class="form-control mr-50" type="search" placeholder="Buscar..." @keyup="searchit" v-model="search">
                            </div>  
                            <div>
                                <button class="btn btn-navbar" @click="searchit"><i class="fa fa-search"></i></button>
                            </div>                          
                            <div>
                                <router-link :to="{name:'inactividadCreate'}" class="btn btn-success">Nueva Inactividad</router-link>
                            </div>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>
                                    <th>Justificativo</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha final</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="inactividad,i in inactividades.data" :key="inactividad.id">
                                    <td>{{i+1}}</td>
                                    <td>{{inactividad.justificante}}</td>
                                    <td>{{fecha_formato(inactividad.fecha_inicio)}}</td>
                                    <td>{{fecha_formato(inactividad.fecha_final)}}</td>
                                    <td>
                                        <router-link :to="{name:'inactividadEdit',params:{id:inactividad.id}}" class="btn-sm btn-info"><i class="fas fa-edit"></i></router-link>
                                        <a @click="inactividadDel(inactividad.id)" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="inactividades" @pagination-change-page="allInactividad"></page-number>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Moment from 'moment';
    export default {
        name: "inactividades",
        data(){
            return {
                inactividades:{},
                search:''      
            }
        },
        methods:{
            searchit(){
                Fire.$emit('searching');
            },
            allInactividad(page){
                axios.get('api/get-inactividades?page=' + page)
                    .then(res=>{
                        this.inactividades = res.data;
                    }).catch(error=>{console.log(error.response.data)});
            },
            inactividadDel(id){
                Swal.fire({
                    title: 'Está seguro?',
                    text: "No prodrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('api/inactividad-delete/'+id)
                            .then(()=>{
                                this.searchit();
                                Notification.error();
                            }).catch(()=>{
                            this.$router.push({name:'inactividades'})
                        })
                    }

                })
            },
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.search;
                axios.get('api/findInactividad?q=' + query)
                .then((data) => {
                    this.inactividades = data.data;
                }) 
                .catch(() => {

                })
            })
            this.allInactividad();
        },
        mounted() {
            if(!User.loggedIn()){
                Toast.fire({
                    icon: 'warning',
                    title: 'Inicia sesión primero!',
                });
                this.$router.push({name:'login'})
            }
        }
    }
</script>

<style scoped>

</style>
