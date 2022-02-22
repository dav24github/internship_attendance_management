<template>

</template>

<script>
    export default {
        name: "Logout",
        created() {
            Swal.fire({
                title: 'Está seguro?',
                text: "Está a punto de cerrar sessión!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => { 
                if (result.value) {               
                    User.logout();
                    Toast.fire({
                        icon: 'success',
                        title: 'Logout exitoso'

                    });
                    this.$router.push({name: 'login'})
                }else{
                    this.$router.push({name: 'home'})
                }
            })
        }
    }
</script>

<style scoped>

</style>