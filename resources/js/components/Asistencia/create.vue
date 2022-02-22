<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Nueva Asistencia</h4>
                            </div>
                            <div>
                                <router-link :to="{name:'asistencias'}" class="btn btn-success">Ver Asistencia</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="asisAdd">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Practicante</label>
                                        <select class="form-control" v-model="form.practicante_id" @change="practicante_horario()">
                                            <option value="">--Seleccionar Nombre--</option>
                                            <option :value="practicante.id" v-for="practicante in practicantes">{{practicante.nombre}}</option>
                                        </select>
                                    </div>
                                </div> 
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Horario de Entrada</label>
                                        <input v-model="form.hr_e" type="time" class="form-control">
                                        <small class="text-danger" v-if="errors.hr_e">{{errors.hr_e[0]}}</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Horario de Salida</label>
                                        <input v-model="form.hr_s" type="time" class="form-control">
                                        <small class="text-danger" v-if="errors.hr_s">{{errors.hr_s[0]}}</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Turno</label>
                                        <select class="form-control " v-model="form.horario_details_id">
                                            <option value="">--Seleccionar Turno--</option>
                                            <option :value="horario_detail.id" v-for="horario_detail in horario_details">
                                                <span v-if="horario_detail.hd_nombre!=null">{{horario_detail.hd_nombre}}</span>
                                                <span v-else>{{horario_detail.h_nombre}}</span>
                                            </option>
                                        </select>
                                    </div>
                                </div>  

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de Ingreso </label>
                                        <input v-model="form.fecha" type="date" class="form-control" placeholder="MM/DD/YYYY">
                                        <small class="text-danger" v-if="errors.fecha">{{errors.fecha[0]}}</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-info"><i class="fas fa-plus"></i>&nbspGuardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "create",
        data(){
            return {                
                horario_details:[],
                practicantes:[],
                form:{
                    horario_details_id:'',                                   
                    practicante_id:'',
                    email:'',
                    hr_e:'',
                    hr_s:'',
                    fecha:'',
                    estado:'',
                },
                errors:{},
            }
        },
        methods:{
            allPracticantes(){
                axios.get('api/get-practicantes')
                    .then(res=>{
                        this.practicantes = res.data;
                        console.log(this.practicantes);
                    }).catch(error=>{console.log(error.response.data)});
            },
            asisAdd(){
                console.log(this.form);
                axios.post('api/add-asistencia-manual',this.form)
                .then(res=>{
                    if(res.data.error){
                        Toast.fire({
                            icon: 'error',
                            title: 'El Email no está registrado!',
                        });
                    }else{                            
                        Notification.success();
                        console.log(res.data);
                        this.$router.push({name:'asistencias'})
                    }
                })
                .catch(error=>{
                    this.errors = error.response.data.errors;
                    console.log(error.response.data.errors)
                })
            },   
            practicante_horario(){
                axios.get('api/horario-details-info/'+this.form.practicante_id)
                    .then(res=>{
                        this.horario_details = res.data;
                        console.log(res.data);
                    }).catch(error=>{console.log(error.response.data)});
            }
        },        
        created(){
            this.allPracticantes();
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
