<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <router-link :to="{name:'horarioIndex'}" class="btn btn-success">Ver Horarios</router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active">
                                        Información del Horario
                                    </a>
                                    <a  class="list-group-item list-group-item-action"><strong>Título: &nbsp</strong>{{horario.h_nombre}}</a>
                                    <a  class="list-group-item list-group-item-action"><strong>Tolerancia: &nbsp</strong> {{horario.tolerancia}}</a>
                                    <br><strong>Horarios:</strong>
                                    <tr v-for="h in details">
                                        <a  v-if="h.hd_nombre" class="list-group-item list-group-item-action"><strong>Turno: &nbsp</strong>{{h.hd_nombre}}</a>
                                        <a  class="list-group-item list-group-item-action"><strong>Entrada: &nbsp</strong>{{h.horario_e}}</a>
                                        <a  class="list-group-item list-group-item-action"><strong>Salida: &nbsp</strong>{{h.horario_s}}</a>
                                    </tr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active">
                                        Practicantes
                                    </a>
                                    <tr v-for="practicante in practicantes" :key="practicante.id">   
                                        <a v-if="practicante.horario_id==horario.id" class="list-group-item list-group-item-action">{{practicante.nombre}} - {{practicante.carrera}}</a>
                                    </tr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "horarioDetails",
        data(){
            return {
                horario:[],
                details:[],
                practicantes:[]
            }
        },
        methods:{
            horarioInfo(){
                let id = this.$route.params.id;
                axios.get('api/horario-info/'+id)
                    .then(res=>{
                        this.horario = res.data;
                    }).catch(error=>{console.log(error.response.data)});
            },
            horarioDetails(){
                let id = this.$route.params.id;
                axios.get('api/horario-details-more/'+id)
                    .then(res=>{
                        this.details = res.data;
                        console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            allPracticantes(){
                let id = this.$route.params.id;
                axios.get('api/get-practicantes')
                    .then(res=>{
                        this.practicantes = res.data;
                    }).catch(error=>{console.log(error.response.data)});
            },
            formatoHorario(hora){
                var splittedString=hora.split(":");
                return splittedString.slice(0,-1).join(':');
            },
        },
        created() {
            this.horarioInfo();
            this.horarioDetails();
            this.allPracticantes();
        }
        ,
        mounted() {
            if(!User.loggedIn()){
                Toast.fire({
                    icon: 'warning',
                    title: 'Login First!',
                });
                this.$router.push({name:'login'})
            }

        },
    }
</script>

<style scoped>

</style>
