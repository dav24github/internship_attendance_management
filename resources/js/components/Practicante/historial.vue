<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Historial de Practicantes</h4>
                            </div>
                            <div>
                                <input class="form-control mr-50" type="search" placeholder="Buscar..." @keyup="searchit" v-model="search">
                            </div>  
                            <div>
                                <button class="btn btn-navbar" @click="searchit"><i class="fa fa-search"></i></button>
                            </div>                          
                            <div>
                                <router-link :to="{name:'practicanteCreate'}" class="btn btn-success">Nuevo practicante</router-link>
                            </div>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <tbody><tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Horario</th>
                                    <th>Carrera</th>
                                    <th>Email</th>                                    
                                    <th>Estado</th>
                                    <th colspan="2">Action</th>
                                </tr>
                                <tr v-for="practicante,i in practicantes.data" :key="practicante.id">                                
                                    <td>
                                        <router-link :to="{name:'practicanteInfo',params:{id:practicante.id}}" class="btn btn-info"><i class="fas fa-info"></i></router-link>
                                    </td>  
                                    <td>{{i+1}}</td>
                                    <td><img :src="practicante.foto" alt="foto" style="width:60px;height:50px"></td>
                                    <td>{{practicante.nombre}}</td>
                                    <td v-if="practicante.horario != null">{{practicante.horario.h_nombre}}</td>
                                    <td v-else></td>
                                    <td>{{practicante.carrera}}</td>
                                    <td>{{practicante.email}}</td>       
                                    <td>
                                        <div v-if="practicante.estado == 1" class="badge badge-success">Activo</div>
                                        <div v-else class="badge badge-danger">Inactivo</div>
                                    </td>                                    
                                    <td colspan="2">                                        
                                        <router-link :to="{name:'practicanteEdit',params:{id:practicante.id}}" class="btn-sm btn-info"><i class="fas fa-edit"></i></router-link>
                                    </td>        
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="practicantes" @pagination-change-page="allPracticante"></page-number>
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
        name: "historial",
        data(){
            return {
                practicantes:[],
                search:''
            }
        },
        methods:{
            searchit(){
                Fire.$emit('searching');
            },
             allPracticante(page){
                axios.get('api/get-historial-practicantes?page=' + page)
                    .then(res=>{
                        this.practicantes = res.data;
                        console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.search;
                axios.get('api/findPracticanteHistorial?q=' + query)
                .then((data) => {
                    this.practicantes = data.data;
                }) 
                .catch(() => {

                })
            })
            this.allPracticante();
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

        },
    }
</script>

<style scoped>

</style>
