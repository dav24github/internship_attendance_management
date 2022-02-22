<template>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Editar Practicante</h4>
                            </div>
                            <div>
                                <router-link
                                    :to="{ name: 'practicanteIndex' }"
                                    class="btn btn-success"
                                    >Ver Practicantes</router-link
                                >
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            @submit.prevent="practicanteEdit"
                            enctype="multipart/form-data"
                        >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input
                                            v-model="form.nombre"
                                            type="text"
                                            class="form-control"
                                            placeholder="Nombre Completo"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.nombre"
                                            >{{ errors.nombre[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Estado</label>
                                        <select
                                            v-model="form.estado"
                                            class="form-control"
                                        >
                                            <option value="">
                                                --select estado--
                                            </option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Horario</label>
                                        <select
                                            v-model="form.horario_id"
                                            class="form-control"
                                        >
                                            <option value="">
                                                --select horario--
                                            </option>
                                            <option
                                                v-for="horario in horarios"
                                                :value="horario.id"
                                            >
                                                {{ horario.h_nombre }}
                                            </option>
                                        </select>
                                        <small
                                            class="text-danger"
                                            v-if="errors.horario_id"
                                            >{{ errors.horario_id[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CI</label>
                                        <input
                                            v-model="form.ci"
                                            type="text"
                                            class="form-control"
                                            placeholder="CI"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.ci"
                                            >{{ errors.ci[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CU</label>
                                        <input
                                            v-model="form.cu"
                                            type="text"
                                            class="form-control"
                                            placeholder="CU"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.cu"
                                            >{{ errors.cu[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Carrera</label>
                                        <input
                                            v-model="form.carrera"
                                            type="text"
                                            class="form-control"
                                            placeholder="Carrera"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.carrera"
                                            >{{ errors.carrera[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email </label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            class="form-control"
                                            placeholder="Email"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.email"
                                            >{{ errors.email[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Telefono </label>
                                        <input
                                            v-model="form.telefono"
                                            type="number"
                                            min="0"
                                            class="form-control"
                                            placeholder="Numero de Telefono"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.telefono"
                                            >{{ errors.telefono[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <input
                                            v-model="form.direccion"
                                            type="text"
                                            class="form-control"
                                            placeholder="Direción"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.direccion"
                                            >{{ errors.direccion[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de Ingreso </label>
                                        <input
                                            v-model="form.f_ingreso"
                                            type="date"
                                            class="form-control"
                                            placeholder="MM/DD/YYYY"
                                        />
                                        <small
                                            class="text-danger"
                                            v-if="errors.f_ingreso"
                                            >{{ errors.f_ingreso[0] }}</small
                                        >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Foto</label>
                                        <input
                                            id="urlFile"
                                            type="file"
                                            class="form-control"
                                            v-on:change="onSelectFile"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <img
                                        v-if="
                                            form.foto !=
                                            'storage/images/user-alt.png'
                                        "
                                        :src="form.foto"
                                        id="imgShow"
                                        style="width: 80px; height: 100px"
                                        class="foto-fluid mb-1"
                                    />
                                    <img
                                        v-if="
                                            form.foto !=
                                            'storage/images/user-alt.png'
                                        "
                                        :src="form.foto"
                                        id="imgHide"
                                        style="
                                            width: 80px;
                                            height: 100px;
                                            display: none;
                                        "
                                        class="foto-fluid mb-1"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button
                                            v-if="
                                                form.foto !=
                                                'storage/images/user-alt.png'
                                            "
                                            id="btnImg"
                                            class="btn btn-secondary btn-sm"
                                            v-on:click.prevent="clearImg()"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <input v-model="form.flag" type="hidden" />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-info">
                                            <i class="fas fa-plus"></i>&nbsp
                                            Actualizar
                                        </button>
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
    data() {
        return {
            form: {
                nombre: "",
                horario_id: "",
                ci: "",
                cu: "",
                carrera: "",
                email: "",
                telefono: "",
                direccion: "",
                estado: "",
                f_ingreso: "",
                foto: "",
                flag: "inicio",
            },
            file: null,
            errors: {},
            horarios: {},
        };
    },
    created() {
        axios.get("api/horarios").then((res) => {
            this.horarios = res.data;
        });

        let id = this.$route.params.id;

        axios
            .get("api/practicantes/" + id)
            .then((res) => {
                this.form = res.data;
            })
            .catch((error) => {});
    },
    methods: {
        practicanteEdit() {
            let id = this.$route.params.id;
            let formData = new FormData();

            formData.append("file", this.file);

            _.each(this.form, (value, key) => {
                if (key != "foto") formData.append(key, value);
            });

            formData.append("_method", "PATCH");

            axios
                .post("api/practicantes/" + id, formData, {
                    headers: {
                        "Content-Type":
                            "multipart/form-data; charset=utf-8; boundary=" +
                            Math.random().toString().substr(2),
                    },
                })
                .then((res) => {
                    Notification.success();
                    this.$router.push({ name: "practicanteIndex" });
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                    console.log(error.response.data.errors);
                });
        },
        onSelectFile(e) {
            let file = e.target.files[0];
            this.file = file;

            if (file.size > 2091540) {
                Notification.imageValidation();
            } else {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $("#imgHide").show();
                    $("#imgShow").hide();
                    $("#btnImg").show();
                    this.form.foto = e.target.result;
                };
                reader.readAsDataURL(file);
            }
            this.form.flag = "lleno";
        },
        clearImg() {
            $("#imgHide").hide();
            $("#imgShow").hide();
            $("#btnImg").hide();
            $("#urlFile").val("");
            this.form.foto = "";
            this.form.flag = "vacio";
        },
    },
    mounted() {
        if (!User.loggedIn()) {
            Toast.fire({
                icon: "warning",
                title: "Inicia sesión primero!",
            });
            this.$router.push({ name: "login" });
        }
    },
};
</script>

<style scoped></style>
