<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active">
                                        Información del Practicante
                                    </a>
                                    <table class="table table-striped table-md">
                                        <br>
                                        <tr>
                                            <td><a><strong>Nombre: &nbsp</strong>{{practicante.nombre}}</a></td>
                                            <td><a><strong>CI: &nbsp</strong> {{practicante.ci}}</a></td>
                                        </tr><br>
                                        <tr>
                                            <td><a><strong>CU: &nbsp</strong> {{practicante.cu}}</a></td>
                                            <td><a><strong>Carrera: &nbsp</strong> {{practicante.carrera}}</a></td>
                                        </tr><br>
                                        <tr>
                                            <td><a><strong>Email: &nbsp</strong> {{practicante.email}}</a></td>                                        
                                            <td><a><strong>Telefono: &nbsp</strong> {{practicante.telefono}}</a></td>
                                        </tr><br>
                                        <tr>
                                            <td><a><strong>Dirección: &nbsp</strong> {{practicante.direccion}}</a></td>
                                            <td v-if="practicante.estado == 1"><a><strong>Estado: &nbsp</strong>Active</a></td>
                                            <td v-else><a><strong>Estado: &nbsp</strong>Inactive</a></td>
                                        </tr><br>
                                        <tr>
                                            <td colspan="2"><a><strong>Fecha de Ingreso: &nbsp</strong> {{fecha_formato(practicante.f_ingreso)}}</a></td>
                                        </tr><br>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active">
                                        Horario: {{practicante.h_nombre}}
                                    </a>
                                    <table class="table table-striped table-md">
                                        <td>
                                            <tr>
                                                <td><a><strong>Turnos:</strong></a></td>
                                            </tr>
                                            <tr v-for="horario in horarios">   
                                                <td>{{horario.hd_nombre}}</td>   
                                                <td>{{horario.horario_e}}</td>   
                                                <td>{{horario.horario_s}}</td>   
                                            </tr>  
                                        </td>
                                    </table>
                                    <a class="list-group-item list-group-item-action active">
                                        Más Infomación
                                    </a>
                                    <br>
                                    <table class="table table-striped table-md">
                                        <td>
                                            <tr><a><strong>Nro. de Faltas: &nbsp</strong>{{nro_faltas}}</a></tr><br>                                            
                                            <tr><a><strong>Días Trabajados: &nbsp</strong>{{nro_diasTrabajados}}</a></tr><br>
                                            <tr><a><strong>Total de Retrasos: &nbsp</strong>{{total_retraso}}</a></tr>
                                        </td>
                                        <td>                                            
                                            <tr><a><strong>Días de Permiso: &nbsp</strong>{{nro_permisos}}</a></tr><br>
                                            <tr><a><strong>Asistencias Completas: &nbsp</strong>{{nro_asistencias}}</a></tr><br>
                                        </td>
                                    </table>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>         

                <div class="card-header">
                    <div class="d-flex justify-content-end">
                        <div>
                            <button v-on:click="showHide('a')" class="btn btn-success">Ver Asistencias</button>
                        </div>&nbsp
                        <div>
                            <button v-on:click="showHide('f')" class="btn btn-success">Ver Faltas</button>
                        </div>&nbsp
                        <div>
                            <button v-on:click="showHide('p')" class="btn btn-success">Ver Permisos</button>
                        </div>&nbsp
                    </div>
                </div>                     
                
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Lista de {{this.titulo}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">

                            <table v-if="this.visto=='f'" class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Nombre</th>
                                    <th>Hr. Entrada</th>
                                    <th>Hr. Salida</th>
                                    <th>Horario</th>
                                </tr>
                                <tr v-for="falta,index in faltas.data">   
                                    <td>{{index+1}}</td>                                 
                                    <td>{{fecha_formato(falta.falta_fecha)}}</td> 
                                    <td>{{falta.nombre}}</td>                                    
                                    <td v-if="falta.hr_entrada">{{falta.hr_entrada}}</td>                                     
                                    <td v-else>--:--</td>
                                    <td>--:--</td>        
                                    <td>{{falta.h_nombre}}-{{falta.hd_nombre}}</td>  
                                </tr>                               
                                </tbody></table>

                                 <table v-if="this.visto=='a'" class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>                                    
                                    <th>Fecha</th>  
                                    <th>Nombre</th>                                 
                                    <th>Hr. entrada</th>
                                    <th>Hr. salida</th>
                                    <th>Retraso</th>
                                    <th>Horario</th> 
                                    <th>Estado</th>
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
                                    <td>{{asistencia.h_nombre}}-{{asistencia.hd_nombre}}</td>  
                                    <td class="badge badge-success" v-if="asistencia.estado=='1'"><i class="fas fa-check-circle"></i></td>                                     
                                    <td class="badge badge-danger" v-else-if="asistencia.estado=='0'"><i class="fas fa-exclamation-circle"></i></td>
                                    <td class="badge badge-success" v-else>Permiso</td>
                                </tr>
                                </tbody></table>

                                 <table v-if="this.visto=='p'" class="table table-striped table-md">
                                <tbody><tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Justificativo</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha final</th>
                                    <th>Horario</th>
                                </tr>
                                <tr v-for="permiso,i in permisos.data" :key="permiso.id">
                                    <td>{{i+1}}</td>
                                    <td>{{permiso.nombre}}</td>
                                    <td>{{permiso.justificante}}</td>
                                    <td>{{fecha_formato(permiso.fecha_inicio)}}</td>
                                    <td>{{fecha_formato(permiso.fecha_final)}}</td>
                                    <td>{{permiso.h_nombre}}-{{permiso.hd_nombre}}</td>
                                </tr>
                                </tbody></table>
                        </div>
                    </div> 
                    <div v-if="pag_falta" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="faltas" @pagination-change-page="faltasInfo"></page-number>
                        </nav>
                    </div>
                    <div v-if="pag_asist" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="asistencias" @pagination-change-page="asistenciasInfo"></page-number>
                        </nav>
                    </div>
                    <div v-if="pag_perm" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="permisos" @pagination-change-page="permisosInfo"></page-number>
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
        name: "practicanteDetails",
        data(){
            return {
                asistencias:{},
                faltas:{},
                permisos:{},
                practicante:[],
                horarios:[],
                nro_faltas:'',
                nro_asistencias:'',
                nro_permisos:'',
                nro_diasTrabajados:'',
                total_retraso:'',
                visto:'a',
                titulo:'Asistencias',
                pag_falta:false,
                pag_asist:true,
                pag_perm:false
            }
        },
        methods:{
            showHide(val) {                 
                this.visto = val;   
                if(this.visto=='a') {
                    this.titulo="Asistencias";                     
                    this.pag_asist=true;
                    this.pag_falta=false;
                    this.pag_perm=false
                };
                if(this.visto=='f') {
                    this.titulo="Faltas";
                    this.pag_asist=false;
                    this.pag_falta=true;
                    this.pag_perm=false
                    };
                if(this.visto=='p'){
                    this.titulo="Permisos"                    
                    this.pag_asist=false;
                    this.pag_falta=false;
                    this.pag_perm=true
                    };
            },
            totalRetraso(){
                let id = this.$route.params.id;
                axios.get('api/total-retraso/'+id)
                    .then(res=>{
                        this.total_retraso = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            nroDiasTrabajados(){
                let id = this.$route.params.id;
                axios.get('api/dias-trabajo-nro/'+id)
                    .then(res=>{
                        this.nro_diasTrabajados = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            nroAsistencias(){
                let id = this.$route.params.id;
                axios.get('api/asistencias-nro/'+id)
                    .then(res=>{
                        this.nro_asistencias = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },            
            nroFaltas(){
                let id = this.$route.params.id;
                axios.get('api/faltas-nro/'+id)
                    .then(res=>{
                        this.nro_faltas = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },            
            nroPermisos(){
                let id = this.$route.params.id;
                axios.get('api/permisos-nro/'+id)
                    .then(res=>{
                        this.nro_permisos = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },

            asistenciasInfo(page){
                let id = this.$route.params.id;
                axios.get('api/asistencias-info/'+id+'?page=' + page)
                    .then(res=>{
                        this.asistencias = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            faltasInfo(page){
                let id = this.$route.params.id;
                axios.get('api/faltas-info/'+id+'?page=' + page)
                    .then(res=>{
                        this.faltas = res.data;
                    }).catch(error=>{console.log(error.response.data)});
            },
            permisosInfo(page){
                let id = this.$route.params.id;
                axios.get('api/permisos-info/'+id+'?page=' + page)
                    .then(res=>{
                        this.permisos = res.data;
                        console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },

            practicanteInfo(){
                let id = this.$route.params.id;
                axios.get('api/practicante-info/'+id)
                    .then(res=>{
                        this.practicante = res.data[0];
                        this.horarios = res.data[1];
                    }).catch(error=>{console.log(error.response.data)});
            },

            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
        },
        created() {
            this.nroDiasTrabajados();
            this.faltasInfo();
            this.practicanteInfo();
            this.nroFaltas();
            this.nroAsistencias();
            this.asistenciasInfo();
            this.permisosInfo();
            this.nroPermisos();
            this.totalRetraso();
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
