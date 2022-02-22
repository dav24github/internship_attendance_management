<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="faltaDate">     
                                    <div class="form-group">                                         
                                        <label>Buscar Por Fecha &nbsp &nbsp</label>
                                        <br>
                                        <input type="date" class="form-control" v-model="form.date">
                                        <br><br>
                                        <button class="btn btn-success mt-2" type="submit">Buscar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="faltaMonth">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">                                            
                                                <label >Rango de Meses</label>                                         
                                                <input type="checkbox" v-on:click="showHide(visto)">
                                            </div>                          
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mes inicial &nbsp &nbsp</label>                
                                                <select v-model="form.i_month" class="form-control " id="">
                                                    <option value="">--Seleccionar Mes--</option>
                                                    <option value="todo">*Ver Todos*</option>
                                                    <option value="1">Enero</option>
                                                    <option value="2">Febrero</option>
                                                    <option value="3">Marzo</option>
                                                    <option value="4">Abril</option>
                                                    <option value="5">Mayo</option>
                                                    <option value="6">Junio</option>
                                                    <option value="7">Julio</option>
                                                    <option value="8">Agosto</option>
                                                    <option value="9">Septiembre</option>
                                                    <option value="10">Octubre</option>
                                                    <option value="11">Noviembre</option>
                                                    <option value="12">Deciembre</option>
                                                </select>
                                                <small class="text-danger" v-if="errors.i_month">{{errors.i_month[0]}}</small>
                                            </div>
                                        </div>   
                                        <div class="col-md-6" v-if="visto">
                                            <div class="form-group">                                             
                                                <label>Mes final &nbsp &nbsp</label>  
                                                <select v-model="form.f_month" class="form-control " id="">
                                                    <option value="">--Seleccionar Mes--</option>
                                                    <option value="todo">*Ver Todos*</option>
                                                    <option value="1">Enero</option>
                                                    <option value="2">Febrero</option>
                                                    <option value="3">Marzo</option>
                                                    <option value="4">Abril</option>
                                                    <option value="5">Mayo</option>
                                                    <option value="6">Junio</option>
                                                    <option value="7">Julio</option>
                                                    <option value="8">Agosto</option>
                                                    <option value="9">Septiembre</option>
                                                    <option value="10">Octubre</option>
                                                    <option value="11">Noviembre</option>
                                                    <option value="12">Deciembre</option>
                                                </select>
                                                <small class="text-danger" v-if="errors.f_month">{{errors.f_month[0]}}</small>
                                            </div>
                                        </div> 
                                        <div class="col-md-12">
                                            <div class="form-group">                                   
                                                <button class="btn btn-success mt-2" type="submit">Buscar</button> 
                                            </div>
                                        </div>
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
                                <h4>Lista de Faltas</h4>
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
                    <div v-if="pag_Month" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="faltas" @pagination-change-page="faltaMonth"></page-number>
                        </nav>
                    </div>
                    <div v-if="pag_Date" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="faltas" @pagination-change-page="faltaDate"></page-number>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "faltaDetails",
        data(){
            return {
                faltas:{},
                form:{
                    date:'',
                    i_month:'',
                    f_month:'',
                },                
                errors:{},
                pag_Date:'',
                pag_Month:'',
                visto: false
            }
        },
        methods:{
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
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
            faltaMonth(page){
                axios.post('api/falta-search-month?page=' + page,this.form)
                    .then(res=>{
                        this.faltas = res.data;
                        console.log(res.data)
                        this.pag_Date=false;
                        this.pag_Month=true;
                    }).catch(error=>{console.log(error.response.data)});
            },
            faltaDate(page){
                axios.post('api/falta-search-date?page=' + page,this.form)
                    .then(res=>{
                        this.faltas = res.data;
                        console.log(res.data)
                        this.pag_Date=true;
                        this.pag_Month=false;
                    }).catch(error=>{console.log(error.response.data)});
            },
            showHide(visto) {      
                if (visto){                    
                    this.form.f_month = ''; 
                }                
                this.visto = !visto;   
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
