<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Historial de Faltas</h4>
                            </div>
                            <div>
                                <input class="form-control mr-50" type="search" placeholder="Buscar..." @keyup="searchit" v-model="search">
                            </div>  
                            <div>
                                <button class="btn btn-navbar" @click="searchit"><i class="fa fa-search"></i></button>
                            </div>    
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Nombre</th>
                                    <th>Hr. Entrada</th>
                                    <th>Hr. Salida</th>
                                    <th>Horario</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="falta,index in faltas.data">   
                                    <th>{{index+1}}</th>                                 
                                    <td>{{fecha_formato(falta.falta_fecha)}}</td> 
                                    <td>{{falta.nombre}}</td>                                    
                                    <td v-if="falta.hr_entrada">{{falta.hr_entrada}}</td>                                     
                                    <td v-else>--:--</td>
                                    <td>--:--</td>        
                                    <td>{{falta.h_nombre}} / <strong>Turno:</strong>{{falta.hd_nombre}}</td>    
                                    <td>
                                        <!--<router-link :to="{name:'asistenciaDetails',params:{id:asistencia.id}}" class="btn-sm btn-info">Details</router-link>-->
                                        <a @click="faltaDel(falta.id)" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td> 
                                </tr>                               
                                </tbody></table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="faltas" @pagination-change-page="allFalta"></page-number>
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
        name: "faltas",
        data(){
            return {
                faltas:{},
                search:'' 
            }
        },
        methods:{
            searchit(){
                Fire.$emit('searching');
            },
            allFalta(page){
                axios.get('api/get-historial-faltas?page=' + page)
                    .then(res=>{
                        this.faltas = res.data;
                        console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },            
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
            faltaDel(id){
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
                        axios.delete('api/falta-delete/'+id)
                            .then(()=>{
                                this.searchit();
                                Notification.error();
                            }).catch(()=>{
                            this.$router.push({name:'faltas'})
                        })
                    }

                })
            },
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.search;
                axios.get('api/findFalta?q=' + query)
                .then((data) => {
                    this.faltas = data.data;
                }) 
                .catch(() => {

                })
            });
            this.allFalta();
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
