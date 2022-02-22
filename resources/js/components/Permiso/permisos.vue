<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>All Permisos</h4>
                            </div>
                            <div>
                                <input class="form-control mr-50" type="search" placeholder="Buscar..." @keyup="searchit" v-model="search">
                            </div>  
                            <div>
                                <button class="btn btn-navbar" @click="searchit"><i class="fa fa-search"></i></button>
                            </div>                              
                            <div>
                                <router-link :to="{name:'permisoCreate'}" class="btn btn-success">Add Permiso</router-link>
                            </div>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>
                                    <th>Nombre</th>                                    
                                    <th>Horario</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha final</th>                                    
                                    <th>Justificativo</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="permiso,index in permisos.data" :key="permiso.id">
                                    <td>{{index+1}}</td>
                                    <td>{{permiso.nombre}}</td>
                                    <td>{{permiso.h_nombre}} / <strong>Turno:</strong>{{permiso.hd_nombre}}</td>
                                    <td>{{fecha_formato(permiso.fecha_inicio)}}</td>
                                    <td>{{fecha_formato(permiso.fecha_final)}}</td>
                                    <td>{{permiso.justificante}}</td>
                                    <td>
                                        <router-link :to="{name:'permisoEdit',params:{id:permiso.id}}" class="btn-sm btn-info"><i class="fas fa-edit"></i></router-link>
                                        <a @click="permisoDel(permiso.id)" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="permisos" @pagination-change-page="allPermiso"></page-number>
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
        name: "permisos",
        data(){
            return {
                permisos:{},
                search:''      
            }
        },
        methods:{
            searchit(){
                Fire.$emit('searching');
            },
            allPermiso(page){
                axios.get('api/get-permisos?page=' + page)
                    .then(res=>{
                        this.permisos = res.data;
                        console.log(this.permisos);
                    }).catch(error=>{console.log(error.response.data)});
            },
            permisoDel(id){
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
                        axios.delete('api/permiso-delete/'+id)
                            .then(()=>{
                                this.searchit();
                                Notification.error();
                            }).catch(()=>{
                            this.$router.push({name:'permisos'})
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
                axios.get('api/findPermiso?q=' + query)
                .then((data) => {
                    this.permisos = data.data;
                }) 
                .catch(() => {

                })
            })
            this.allPermiso();
        }
        ,
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
