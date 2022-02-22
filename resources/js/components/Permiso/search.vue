<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="permisoDate">
                                    <label>Buscar por Fecha</label>
                                    <input type="date" class="form-control" v-model="form.date">
                                    <button class="btn btn-success mt-2" type="submit">Buscar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="permisoMonth">
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
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Lista de Permisos</h4>
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
                                <tr v-for="permiso,i in permisos.data" :key="permiso.id">
                                    <td>{{i+1}}</td>
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
                    <div v-if="pag_Date" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="permisos" @pagination-change-page="permisoDate"></page-number>
                        </nav>
                    </div>
                    <div v-if="pag_Month" class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="permisos" @pagination-change-page="permisoMonth"></page-number>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "permisoDetails",
        data(){
            return {
                permisos:{},
                form:{
                    date:'',
                    month:''
                },
                pag_Date:'',
                pag_Month:'',
            }
        },
        methods:{
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
            permisoMonth(page){
                axios.post('api/permiso-search-month?page=' + page,this.form)
                    .then(res=>{
                        this.permisos = res.data;
                        console.log(res.data)
                        this.pag_Date=false;
                        this.pag_Month=true;
                    }).catch(error=>{console.log(error.response.data)});
            },
            permisoDate(page){
                axios.post('api/permiso-search-date?page=' + page,this.form)
                    .then(res=>{
                        this.permisos = res.data;
                        console.log(res.data)
                        this.pag_Date=true;
                        this.pag_Month=false;
                    }).catch(error=>{console.log(error.response.data)});
            }
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
