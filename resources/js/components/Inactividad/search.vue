<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="inactividadMonth">
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
                                <h4>Lista de Inactividades</h4>
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
                            <page-number :data="inactividades" @pagination-change-page="inactividadMonth"></page-number>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "inactividadDetails",
        data(){
            return {
                inactividades:{},
                form:{
                    month:'',
                },
            }
        },
        methods:{
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
            inactividadMonth(page){
                axios.post('api/inactividad-search-month?page=' + page,this.form)
                    .then(res=>{
                        this.inactividades = res.data;
                        console.log(res.data)
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
