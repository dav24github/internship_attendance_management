<template>
    <div>
        <div class="row ">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12 d-flex justify-content-center">
                <vue-clock />
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Asistencias de Hoy</h4>
                            </div>
                            <div>
                                <input class="form-control mr-50" type="search" placeholder="Buscar..." @keyup="searchit" v-model="search">
                            </div>  
                            <div>
                                <button class="btn btn-navbar" @click="searchit"><i class="fa fa-search"></i></button>
                            </div>                             
                        </div>
                        <div>
                            <button v-on:click="updateAsistencias" class="btn btn-success">Actualizar</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>                                    
                                    <th>Fecha</th>  
                                    <th>Nombre</th>                                 
                                    <th>Hr. entrada</th>
                                    <th>Hr. salida</th>
                                    <th>Retraso</th>
                                    <th>Horario</th> 
                                    <th>Estado</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="(asistencia,i) in asistencias.data" :key="asistencia.id">
                                    <td>{{i+1}}</td>                                    
                                    <td>{{fecha_formato(asistencia.asist_fecha)}}</td>
                                    <td>{{asistencia.nombre}}</td>
                                    <td v-if="asistencia.h_entrada">{{asistencia.h_entrada}}</td>                                     
                                    <td v-else>--:--</td>
                                    <td v-if="asistencia.h_salida">{{asistencia.h_salida}}</td>                                     
                                    <td v-else>--:--</td>
                                    <td>{{asistencia.retraso}}</td>
                                    <td>{{asistencia.h_nombre}} / <strong>Turno:</strong>{{asistencia.hd_nombre}}</td>  
                                    <td class="badge badge-success" v-if="asistencia.estado=='1'"><i class="fas fa-check-circle"></i></td>                                     
                                    <td class="badge badge-danger" v-else><i class="fas fa-exclamation-circle"></i></td>
                                    <td>
                                        <a @click="asistenciaDel(asistencia.id)" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="asistencias" @pagination-change-page="todayAsistencias"></page-number>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import Moment from 'moment'
    import VueClock from '@dangvanthanh/vue-clock';
    export default {
        name: "home",
        components: { VueClock },
        data(){
            return {
                asistencias:{},
                search:'',
            }
        },
        methods:{
            updateAsistencias(){                
                this.todayAsistencias();
            },
            searchit(){
                Fire.$emit('searching');
            },
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
            todayAsistencias(page){
                axios.get('api/today-asistencias?page=' + page)
                    .then(res=>{
                        this.asistencias = res.data;
                        console.log(this.asistencias);
                    }).catch(error=>{console.log(error.response.data)});
            },
            asistenciaDel(id){
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
                        axios.delete('api/asistencia-delete/'+id)
                            .then(()=>{
                                this.searchit();
                                Notification.error();
                            }).catch(()=>{
                            this.$router.push({name:'asistencias'})
                        })
                    }

                })
            },
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.search;
                axios.get('api/findAsistencia?q=' + query)
                .then((data) => {
                    this.asistencias = data.data;
                }) 
                .catch(() => {

                })
            })
            this.todayAsistencias();
        },
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
