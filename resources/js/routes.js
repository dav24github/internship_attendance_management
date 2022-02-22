// auth routes
let login = require("./components/Auth/Login.vue").default;
let register = require("./components/Auth/Register.vue").default;
let logout = require("./components/Auth/Logout.vue").default;

// dashboard routes
let home = require("./components/Home.vue").default;

// practicante routes
let practicanteCreate = require("./components/Practicante/create.vue").default;
let practicanteIndex = require("./components/Practicante/index.vue").default;
let practicanteHistorial =
    require("./components/Practicante/historial.vue").default;
let practicanteEdit = require("./components/Practicante/edit.vue").default;
let practicanteInfo =
    require("./components/Practicante/practicanteDetails.vue").default;

// horario routes
let horarioCreate = require("./components/Horario/create.vue").default;
let horarioIndex = require("./components/Horario/index.vue").default;
let horarioEdit = require("./components/Horario/edit.vue").default;
let horarioDetails = require("./components/Horario/horarioDetails.vue").default;

//asistencia routes
let asistenciaCreate = require("./components/Asistencia/create.vue").default;
let asistencias = require("./components/Asistencia/asistencias.vue").default;
let searchAsistencia = require("./components/Asistencia/search.vue").default;
let historialAsistencia =
    require("./components/Asistencia/historial.vue").default;

//retraso routes
let searchRetraso = require("./components/Retraso/search.vue").default;

// inactividad routes
let inactividadCreate = require("./components/Inactividad/create.vue").default;
let inactividadIndex =
    require("./components/Inactividad/inactividades.vue").default;
let inactividadEdit = require("./components/Inactividad/edit.vue").default;
let searchInactividad = require("./components/Inactividad/search.vue").default;

// permiso routes
let permisoCreate = require("./components/Permiso/create.vue").default;
let permisoIndex = require("./components/Permiso/permisos.vue").default;
let permisoEdit = require("./components/Permiso/edit.vue").default;
let searchPermiso = require("./components/Permiso/search.vue").default;

//falta routes
let faltas = require("./components/Falta/faltas.vue").default;
let searchFalta = require("./components/Falta/search.vue").default;
let historialFalta = require("./components/Falta/historial.vue").default;

let puntoMarcaje = require("./components/PuntoMarcaje.vue").default;

let generarPdf = require("./components/GenerarPdf.vue").default;

export const routes = [
    { path: "/", component: login, name: "login" },
    { path: "/register", component: register, name: "register" },
    { path: "/logout", component: logout, name: "logout" },

    // dashboard routes
    { path: "/home", component: home, name: "home" },

    // practicante routes
    {
        path: "/add-practicante",
        component: practicanteCreate,
        name: "practicanteCreate",
    },
    {
        path: "/practicantes",
        component: practicanteIndex,
        name: "practicanteIndex",
    },
    {
        path: "/historial",
        component: practicanteHistorial,
        name: "practicanteHistorial",
    },
    {
        path: "/edit-practicante/:id",
        component: practicanteEdit,
        name: "practicanteEdit",
    },
    {
        path: "/practicante-info/:id",
        component: practicanteInfo,
        name: "practicanteInfo",
    },

    // horario routes
    { path: "/add-horario", component: horarioCreate, name: "horarioCreate" },
    { path: "/horarios", component: horarioIndex, name: "horarioIndex" },
    { path: "/horario-edit/:id", component: horarioEdit, name: "horarioEdit" },
    {
        path: "/horario-details/:id",
        component: horarioDetails,
        name: "horarioDetails",
    },

    // inactividad routes
    {
        path: "/add-inactividad",
        component: inactividadCreate,
        name: "inactividadCreate",
    },
    {
        path: "/inactividades",
        component: inactividadIndex,
        name: "inactividadIndex",
    },
    {
        path: "/inactividad-edit/:id",
        component: inactividadEdit,
        name: "inactividadEdit",
    },
    {
        path: "/inactividad-search",
        component: searchInactividad,
        name: "searchInact",
    },

    // permiso routes
    { path: "/add-permiso", component: permisoCreate, name: "permisoCreate" },
    { path: "/permisos", component: permisoIndex, name: "permisoIndex" },
    { path: "/permiso-edit/:id", component: permisoEdit, name: "permisoEdit" },
    { path: "/permiso-search", component: searchPermiso, name: "searchPerm" },

    //asistencia routes
    {
        path: "/add-asistencia",
        component: asistenciaCreate,
        name: "asistenciaCreate",
    },
    { path: "/asistencias", component: asistencias, name: "asistencias" },
    {
        path: "/asistencia-search",
        component: searchAsistencia,
        name: "searchAsistencia",
    },
    {
        path: "/asistencia-historial",
        component: historialAsistencia,
        name: "historialAsistencia",
    },

    //retraso routes
    {
        path: "/retraso-search",
        component: searchRetraso,
        name: "searchRetraso",
    },

    //faltas routes
    { path: "/faltas", component: faltas, name: "faltas" },
    { path: "/falta-search", component: searchFalta, name: "searchFalta" },
    {
        path: "/falta-historial",
        component: historialFalta,
        name: "historialFalta",
    },

    { path: "/punto-marcaje", component: puntoMarcaje, name: "puntoMarcaje" },

    { path: "/generar-pdf", component: generarPdf, name: "generarPdf" },
];
