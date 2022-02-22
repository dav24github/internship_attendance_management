<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">                            
                                <form @submit.prevent="asistenciaDate">                                    
                                    <div class="form-group">
                                        <label>Buscar por Fecha</label>
                                        <input type="date" class="form-control" v-model="form.date">
                                        <button class="btn btn-success mt-2" type="submit">Buscar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="asistenciaMonth">
                                    <div class="form-group">
                                        <label>Buscar por Mes</label>
                                        <select v-model="form.month" class="form-control " id="">
                                            <option value="">--Seleccionar Mes--</option>
                                            <option value="January">Enero</option>
                                            <option value="February">Febrero</option>
                                            <option value="March">Marzo</option>
                                            <option value="April">Abril</option>
                                            <option value="May">Mayo</option>
                                            <option value="June">Junio</option>
                                            <option value="July">Julio</option>
                                            <option value="August">Agosto</option>
                                            <option value="September">Septiembre</option>
                                            <option value="October">Octubre</option>
                                            <option value="November">Noviembre</option>
                                            <option value="December">Deciembre</option>                                       
                                            <option value="todo">*Ver Todo*</option>
                                        </select>
                                        <button class="btn btn-success mt-2" type="submit">Buscar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Lista de Asistencias</h4>
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
                                    <td>{{asistencia.h_entrada}}</td>
                                    <td v-if="asistencia.h_salida">{{asistencia.h_salida}}</td>                                     
                                    <td v-else>---</td>
                                    <td>{{asistencia.retraso}}</td>
                                    <td>{{asistencia.h_nombre}} / <strong>Turno:</strong>{{asistencia.hd_nombre}}</td>  
                                    <td class="badge badge-success" v-if="asistencia.estado=='1'"><i class="fas fa-check-circle"></i></td>                                     
                                    <td class="badge badge-danger" v-else><i class="fas fa-exclamation-circle"></i></td>
                                    <td>
                                        <!--<router-link :to="{name:'asistenciaDetails',params:{id:asistencia.id}}" class="btn-sm btn-info">Details</router-link>-->
                                        <a @click="asistenciaDel(asistencia.id)" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                    <div v-if="pag_Month" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="asistencias" @pagination-change-page="asistenciaMonth"></page-number>
                        </nav>
                    </div>
                    <div v-if="pag_Date" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="asistencias" @pagination-change-page="asistenciaDate"></page-number>
                        </nav>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "asistenciaDetails",
        data(){
            return {
                asistencias:{},
                form:{
                    date:'',
                    month:'',
                },
                pag_Date:'',
                pag_Month:''
            }
        },
        methods:{
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
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
            asistenciaMonth(page){
                axios.post('api/asistencia-search-month?page=' + page,this.form)
                    .then(res=>{
                        this.asistencias = res.data;
                        console.log(res.data);
                        this.pag_Date=false;
                        this.pag_Month=true;
                    }).catch(error=>{console.log(error.response.data)});
            },
            asistenciaDate(page){
                axios.post('api/asistencia-search-date?page=' + page,this.form)
                    .then(res=>{
                        this.asistencias = res.data;
                        console.log(res.data)                        
                        this.pag_Date=true;
                        this.pag_Month=false;
                    }).catch(error=>{console.log(error.response.data)});
            },
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
