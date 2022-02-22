<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Editar Inactividad</h4>
                            </div>
                            <div>
                                <router-link :to="{name:'inactividadIndex'}" class="btn btn-success">Ver Inactividades</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="inactividadEdit" enctype="multipart/form-data">
                            <div class="row">
                               <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Justificante </label>
                                        <input v-model="form.justificante" type="text" class="form-control" placeholder="Justificante">
                                        <small class="text-danger" v-if="errors.justificante">{{errors.justificante[0]}}</small>
                                    </div>
                                </div>    

                                <div class="col-md-12 form-group">                         
                                    <label >Rango de Fecha</label>                                         
                                    <input type="checkbox" v-on:click="showHide(visto)" v-model="is_checked"> 
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de Inicio</label>
                                        <input v-model="form.fecha_inicio" type="date" class="form-control" placeholder="MM/DD/YYYY">
                                        <small class="text-danger" v-if="errors.fecha_inicio">{{errors.fecha_inicio[0]}}</small>
                                    </div>
                                </div>

                                <div class="col-md-6" v-if="visto">
                                    <div class="form-group">
                                        <label>Fecha Final</label>
                                        <input v-model="form.fecha_final" type="date" class="form-control" placeholder="MM/DD/YYYY">
                                        <small class="text-danger" v-if="errors.fecha_final">{{errors.fecha_final[0]}}</small>
                                    </div>
                                </div>            
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-info"><i class="fas fa-edit"></i>&nbsp Actualizar</button>
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
        name: "edit",
        data(){
            return {
                form:{
                    justificante:'',
                    fecha_inicio:'',
                    fecha_final:'',
                },
                errors:{},
                visto: false,
                is_checked: '',
            }
        },
        created() {
            let id = this.$route.params.id;
            axios.get('api/inactividades/'+id)
            .then(res=>{
                this.form = res.data;
                console.log(res.data);                
                
                this.isChecked(this.form.fecha_final);
            })
                .catch(error=>{});

        },
        methods:{
            inactividadEdit(){
                let id = this.$route.params.id;
                axios.patch('api/inactividad-edit/'+id,this.form)
                    .then(res=>{
                        Notification.success();
                        this.$router.push({name:'inactividadIndex'})
                    })
                    .catch(error=>{
                        this.errors = error.response.data.errors;
                        console.log(error.response.data.errors)
                    });
            },                       
            showHide(visto) {      
                if (visto){                    
                    this.form.fecha_final = ''; 
                }                
                this.visto = !visto;
            },
            isChecked(valor){
                if(valor!=""){
                    this.is_checked='checked';
                    this.visto = true;
                }
                else
                    this.is_checked='';
            }
        },
        mounted() {
            if(!User.loggedIn()){
                Toast.fire({
                    icon: 'warning',
                    title: 'Inicia sesión primero!',
                });
                this.$router.push({name:'login'})
            };
        },
    }
</script>

<style scoped>

</style>
