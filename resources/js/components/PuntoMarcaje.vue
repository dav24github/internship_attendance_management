<template>
    <div class="container">
        <div class="row ">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-5 offset-xl-2 d-flex justify-content-center">
                    <vue-clock/>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-5 offset-xl-2">
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-center">
                        <h4>Control de Asistencia</h4>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="asisAdd">
                            <div class="form-group">
                                 <label>Email <span class="text-danger">*</span></label>
                                <input v-model="form.email" type="email" class="form-control" placeholder="Email">
                                <small class="text-danger" v-if="errors.email">{{errors.email[0]}}</small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    <h5><i class="fas fa-user-clock"></i>&nbsp  Marcar</h5>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import VueClock from '@dangvanthanh/vue-clock';
    export default {
        name: "create",
        components: { VueClock },
        data(){
            return {               
                form:{
                    email:'',
                },
                errors:{},
            }
        },
        methods:{
            asisAdd(){          
                console.log(this.form);
                axios.post('api/add-asistencia',this.form)
                .then(res=>{
                    if(res.data.error){   
                        if(res.data.error=="email")
                            Toast.fire({
                                icon: 'error',
                                title: 'El Email no está registrado!',
                            });
                        if(res.data.error=="salida")
                            Toast.fire({
                                icon: 'error',
                                title: 'Aun no es la hora de Salida!',
                            });
                        if(res.data.error=="hoy")   
                            Toast.fire({
                                icon: 'error',
                                title: 'Ya realizaste todas tus marcaciones de hoy!',
                            });                     
                    }else{   
                        this.form.email="";                         
                        Notification.success();
                        console.log(res.data);
                    }
                })
                .catch(error=>{
                    this.errors = error.response.data.errors;
                    console.log(error.response.data.errors)
                })
            },   
        }
    }
</script>

<style scoped>

</style>
