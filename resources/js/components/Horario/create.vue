<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Nuevo Horario</h4>
                            </div>
                            <div>
                                <router-link :to="{name:'horarioIndex'}" class="btn btn-success">Ver Horarios</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="horarioAdd">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input v-model="form.h_nombre" type="text" class="form-control" placeholder="Nombre del Horario">
                                        <small class="text-danger" v-if="errors.h_nombre">{{errors.h_nombre[0]}}</small>
                                    </div>
                                </div>                            

                                <div class="col-md-3" v-for="(input,k) in form.inputs" :key="k">
                                    <div class="form-group col-md-12">
                                        <label>Turno (opcional)</label>
                                        <input type="text" class="form-control" v-model="input.hd_nombre" placeholder="Nombre del Turno">
                                        <label>Hora de entrada</label>
                                        <input type="time" class="form-control" v-model="input.h_e">
                                        <label>Hora de salida</label>
                                        <input type="time" class="form-control" v-model="input.h_s">                                        
                                        <span>
                                            <i class="fas fa-minus-circle" @click="remove(k)" v-show="k || ( !k && form.inputs.length > 1)"></i>
                                            <i class="fas fa-plus-circle" @click="add(k)" v-show="k == form.inputs.length-1"></i>
                                        </span>  
                                    </div>                                  
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tolerancia (min.)</label>
                                        <input v-model="form.tolerancia" type="number" class="form-control" placeholder="Tolerancia">
                                        <small class="text-danger" v-if="errors.tolerancia">{{errors.tolerancia[0]}}</small>
                                    </div>
                                </div>                                

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-info"><i class="fas fa-plus"></i>&nbsp Guardar</button>
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
                form:{
                    h_nombre:'',
                    tolerancia:'',
                    inputs: [
                        {
                            hd_nombre:'',
                            h_e: '',
                            h_s: '',
                        }
                    ],
                },
                errors:{},
            }
        },
        methods:{
            horarioAdd(){
                axios.post('api/horarios',this.form)
                .then(res=>{
                    console.log(res);
                    Notification.success();
                    this.$router.push({name:'horarioIndex'}) //router-link   
                })
                .catch(error=>{
                    this.errors = error.response.data.errors;
                    console.log(error.response.data.errors)
                })
                console.log(this.form);
            },
            add(index) {
                this.form.inputs.push({ hd_nombre:'',h_e: '',h_s: ''});
            },
            remove(index) {
                this.form.inputs.splice(index, 1);
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
