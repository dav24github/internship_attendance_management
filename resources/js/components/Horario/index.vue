<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div>
                                <h4>Lista de Horarios</h4>
                            </div>
                            <div>
                                <input class="form-control mr-50" type="search" placeholder="Buscar..." @keyup="searchit" v-model="search">
                            </div>  
                            <div>
                                <button class="btn btn-navbar" @click="searchit"><i class="fa fa-search"></i></button>
                            </div>   
                            <div>
                                <router-link :to="{name:'horarioCreate'}" class="btn btn-success">Nuevo horarios</router-link>
                            </div>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <tbody><tr>
                                    <th style="width:1%"></th>
                                    <th>#</th>
                                    <th>Título</th>
                                    <th>Tolerancia (min.)</th>
                                </tr>
                                <tr v-for="horario,i in horarios.data" :key="horario.id">
                                    <td style="width:1%">
                                        <router-link :to="{name:'horarioDetails',params:{id:horario.id}}" class="btn btn-info"><i class="fas fa-info"></i></router-link>
                                    </td>
                                    <td>{{i+1}}</td> 
                                    <td>{{horario.h_nombre}}</td>                                                 
                                    <td>{{horario.tolerancia}}</td>
                                                                        
                                    <td colspan="2">
                                        <router-link :to="{name:'horarioEdit',params:{id:horario.id}}" class="btn-sm btn-info"><i class="fas fa-edit"></i></router-link>
                                        <a @click="horariosDel(horario.id)" class="btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                   
                                </tr>
                                </tbody></table>
                        </div>
                    </div>
                   <div class="card-footer text-right">
                        <nav class="d-inline-block">
                            <page-number :data="horarios" @pagination-change-page="allHorario"></page-number>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "index",
        data(){
            return {
                horarios:{},
                search:''   
            }
        },
        methods:{
            searchit(){
                Fire.$emit('searching');
            },
            allHorario(page){
                axios.get('api/get-horarios?page=' + page)
                    .then(res=>{
                        this.horarios = res.data;
                        console.log(this.horarios);
                    }).catch(error=>{console.log(error.response.data)});
            },
            horariosDel(id){
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
                        axios.delete('api/horarios/'+id)
                            .then(()=>{
                                Notification.error();
                                this.searchit();
                            }).catch(()=>{
                            this.$router.push({name:'horarioIndex'})
                        })
                        }

                })
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.search;
                axios.get('api/findHorario?q=' + query)
                .then((data) => {
                    this.horarios = data.data;
                }) 
                .catch(() => {

                })
            })
            this.allHorario();
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

        }
    }
</script>

<style scoped>

</style>
