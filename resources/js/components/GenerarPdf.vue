<template>
    <div class="container">        
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">                
                <button v-if="visto_form_p" color='black' class="btn btn-success mt-2 active" v-on:click="showHide_Form_p()">Practicantes</button>
                <button v-else color='black' class="btn btn-success mt-2" v-on:click="showHide_Form_p()">Practicantes</button>
                &nbsp
                <button v-if="visto_form_i" color='black' class="btn btn-success mt-2 active" v-on:click="showHide_Form_i()">Inactividades</button>
                <button v-else color='black' class="btn btn-success mt-2" v-on:click="showHide_Form_i()">Inactividades</button>
                <br><br>
                <form @submit.prevent="getInforme">
                    <div class="row" v-if="visto_form_p">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div>
                                                <h6>Practicantes</h6><br>
                                            </div>  
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <select class="form-control" v-model="form.practicante_id" @change="change()">
                                                    <option value="">--Seleccionar Nombre--</option>
                                                    <option :value="practicante.id" v-for="practicante in practicantes">{{practicante.nombre}}</option>
                                                    <option value="todo">*Todos*</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Tipo de Informe</label>
                                                <select v-model="form.tipo" class="form-control " id="">
                                                    <option value="">--Seleccionar Tipo--</option>        
                                                    <option value="asistencias">Asistencias</option>
                                                    <option value="faltas">Faltas</option>
                                                    <option value="permisos">Permisos</option>      
                                                    <option value="todo">*Todos*</option>     
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">                                
                                    <div class="row">                                    
                                        <div class="col-md-12">
                                            <div class="form-group">                                            
                                                <label >Rango de fechas</label>                                         
                                                <input type="checkbox" v-on:click="showHide_p(visto_p)" checked>
                                            </div>                          
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fecha inicial &nbsp &nbsp</label>     
                                                <input type="date" class="form-control" v-model="form.i_date_practicante">
                                                <small class="text-danger" v-if="errors.i_month">{{errors.i_month[0]}}</small>
                                            </div>
                                        </div>   
                                        <div class="col-md-6" v-if="visto_p">
                                            <div class="form-group">                                             
                                                <label>Fecha final &nbsp &nbsp</label>                                                  
                                                <input type="date" class="form-control" v-model="form.f_date_practicante">
                                                <small class="text-danger" v-if="errors.f_month">{{errors.f_month[0]}}</small>
                                            </div>
                                        </div> 
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <input type="hidden" v-model="form.flag_p" class="form-control " id="">
                    </div>
                    <div class="row" v-if="visto_form_i">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">                                
                                    <div class="row">                               
                                        <div class="col-md-12">                                            
                                            <div>
                                                <h6>Inactividades</h6><br>
                                            </div>     
                                            <div class="form-group">                                            
                                                <label >Rango de fechas</label>                                         
                                                <input type="checkbox" v-on:click="showHide_i(visto_i)" checked>
                                            </div>                          
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fecha inicial &nbsp &nbsp</label>     
                                                <input type="date" class="form-control" v-model="form.i_date_inactividad">
                                                <small class="text-danger" v-if="errors.i_month">{{errors.i_month[0]}}</small>
                                            </div>
                                        </div>   
                                        <div class="col-md-6" v-if="visto_i">
                                            <div class="form-group">                                             
                                                <label>Fecha final &nbsp &nbsp</label>                                                  
                                                <input type="date" class="form-control" v-model="form.f_date_inactividad">
                                                <small class="text-danger" v-if="errors.f_month">{{errors.f_month[0]}}</small>
                                            </div>
                                        </div> 
                                    </div>                                
                                </div>
                            </div>
                        </div>                                               
                        <input type="hidden" v-model="form.flag_i" class="form-control " id="">
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body d-flex justify-content-center">
                                    <button color='black' class="btn btn-danger col-md-12">Generar PDF</button>
                                </div>
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import jsPDF from 'jspdf';
    import 'jspdf-autotable';
    import Moment from 'moment';
    export default {
        name: "generarpdf",
        data(){
            return { 
                practicantes:[],
                form:{ 
                    flag_p:true,
                    flag_i:"",
                    tipo:'',
                    practicante_id:'',
                    i_date_practicante:'',
                    f_date_practicante:'',                    
                    i_date_inactividad:'',
                    f_date_inactividad:'',
                },    
                heading: "",
                
                practicante: [],
                horarios: [],
                nro_faltas:'',
                nro_asistencias:'',
                nro_permisos:'',
                nro_diasTrabajados:'',
                total_retraso:'',

                items: [],
                itemsTodo: [],
                errors:{},
                visto_p: true,
                visto_i: true,
                visto_form_p: true,
                visto_form_i: false
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
            getInforme(){  
                if(this.form.tipo=="todo" && this.form.flag_p==true){
                    this.nroDiasTrabajados();
                    this.nroFaltas();
                    this.nroAsistencias();
                    this.nroPermisos();
                    this.totalRetraso();
                    axios.post('api/get-informe',this.form)
                    .then(res=>{
                        this.itemsTodo = res.data;
                        console.log(res.data);  
                        if(this.form.practicante_id!="todo")   
                            this.generatePdfTodoPracticante(); 
                        else  
                            this.generatePdfTodo();                    
                    }).catch(error=>{console.log(error.response.data)});
                }else{
                    axios.post('api/get-informe',this.form)
                    .then(res=>{
                        this.items = res.data;
                        console.log(res.data);                            
                        if(this.form.flag_p==true){  
                            this.nroDiasTrabajados();
                            this.nroFaltas();
                            this.nroAsistencias();
                            this.nroPermisos();
                            this.totalRetraso();                          
                            if(this.form.practicante_id=="todo"){
                                if(this.form.tipo=="asistencias")
                                    this.generatePdfAsistencias();
                                if(this.form.tipo=="faltas")
                                    this.generatePdfFaltas();
                                if(this.form.tipo=="permisos")
                                    this.generatePdfPermisos();
                            }else{
                                if(this.form.tipo=="asistencias")
                                    this.generatePdfPracticanteAsistencias();
                                if(this.form.tipo=="faltas")
                                    this.generatePdfPracticanteFaltas();
                                if(this.form.tipo=="permisos")
                                    this.generatePdfPracticantePermisos();
                            }
                        }
                        if(this.form.flag_i==true)
                            this.generatePdfInactividad();
                    }).catch(error=>{console.log(error.response.data)});
                }                
            },

            generatePdfTodo(){
                var columns1 = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "asist_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "h_entrada" },
                    { title: "Hr. salida", dataKey: "h_salida" },
                    { title: "Retraso", dataKey: "retraso" },
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Estado", dataKey: "estado" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);
                
                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.2;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);

                doc.setFontSize(12).text("LISTA DE ASISTENCIAS", 0.9, 1.5);

                let index=1;
                this.itemsTodo[0].forEach( function(valor, indice, array) {
                    if(valor.estado==0)
                        valor.estado = "Incompleto";
                    if(valor.estado==1)
                        valor.estado = "Completo";
                    if(valor.estado==3)
                        valor.estado = "Permiso";
                    if(valor.h_entrada=="") valor.h_entrada = "--:--";
                    if(valor.h_salida=="") valor.h_salida = "--:--";
                    if(valor.retraso=="") valor.retraso = "--:--";
                    valor.asist_fecha =  moment( valor.asist_fecha ).format("DD/MM/YYYY")
                    valor.number = index;
                    index++
                });

                doc.autoTable({
                    columns: columns1,
                    body: this.itemsTodo[0],
                    margin: { left: 0.5, top: 1.6}
                });
                
                index=1;
                this.itemsTodo[1].forEach( function(valor, indice, array) {
                    if(valor.hr_entrada=="") valor.hr_entrada = "--:--";
                    
                    valor.hr_salida = "--:--";

                    valor.falta_fecha =  moment( valor.falta_fecha ).format("DD/MM/YYYY")

                    valor.number = index;
                    index++
                });
                
                var columns2 = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "falta_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "hr_entrada" },
                    { title: "Hr. salida", dataKey: "hr_salida" },
                    { title: "Turno", dataKey: "hd_nombre" },
                ];
                doc.addPage();

                doc.setFontSize(12).text("LISTA DE FALTAS", 0.9, 1.0);
                doc.autoTable({
                    columns: columns2,
                    body: this.itemsTodo[1],
                    margin: { left: 0.5, top: 1.2}
                });

                index=1;
                this.itemsTodo[2].forEach( function(valor, indice, array) {
                    valor.fecha_inicio =  moment( valor.fecha_inicio ).format("DD/MM/YYYY");
                    if(valor.fecha_final != "") valor.fecha_final =  moment( valor.fecha_final ).format("DD/MM/YYYY");
                    valor.number = index;
                    index++
                });

                var columns3 = [
                    { title: "#", dataKey: "number" },                    
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Fecha de inicio", dataKey: "fecha_inicio" },
                    { title: "Fecha final", dataKey: "fecha_final" },
                    { title: "Justificante", dataKey: "justificante" }
                ];

                doc.addPage();

                doc.setFontSize(12).text("LISTA DE PERMISOS", 0.9, 1.0);
                doc.autoTable({
                    columns: columns3,
                    body: this.itemsTodo[2],
                    margin: { left: 0.5, top: 1.2}
                });

                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save("Informe General.pdf");
            },

            generatePdfTodoPracticante(){
                var columns1 = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "asist_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "h_entrada" },
                    { title: "Hr. salida", dataKey: "h_salida" },
                    { title: "Retraso", dataKey: "retraso" },
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Estado", dataKey: "estado" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);


                let pdfWidth = doc.internal.pageSize.getWidth()/2;

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.4;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.2;
                doc
                    .setFontSize(12)
                    .text("INFORMACIÓN DEL PRACTICANTE", 0.9, salto, { align: "left", maxWidth: "7.5" }); 
                salto += 0.1;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.3;
                let salto1 = salto;
                doc
                    .setFontSize(11)
                    .text("NOMBRE: " + this.practicante.nombre, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CI: " + this.practicante.ci, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CU: " + this.practicante.cu, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4;                 
                doc
                    .setFontSize(11)
                    .text("CARRERA: " + this.practicante.carrera, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4;    
                doc
                    .setFontSize(11)
                    .text("EMAIL: " +  this.practicante.email, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("TELÉFONO: " + this.practicante.telefono, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("DIRECCIÓN: " + this.practicante.direccion, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("FECHA INGRESO: " + this.fecha_formato(this.practicante.f_ingreso), 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                
                salto1 += 0.4; 
                //HorarioInfo
                doc.setLineWidth(0.01).line(0.8, salto1, 7.7, salto1);
                let salto2 = salto1 + 0.2;
                doc
                    .setFontSize(12)
                    .text("HORARIO: " + this.practicante.h_nombre, 0.9, salto2, { align: "left", maxWidth: "7.5" });   
                salto2 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);

                salto2 += 0.3;
                doc
                    .setFontSize(12)
                   .text("TURNOS:", 1.1, salto2, { align: "left", maxWidth: "7.5" });  

                this.horarios.forEach( function(valor, indice, array) {
                    doc
                        .setFontSize(12)
                        .text(valor.hd_nombre + "  =>  " + valor.horario_e + "  -  " + valor.horario_s, 2, salto2, { align: "left", maxWidth: "7.5" });  
                    salto2 += 0.4; 
                });
                    
                //MoreInfo
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);
                let salto3 = salto2 + 0.2;
                doc
                    .setFontSize(12)
                    .text("MÁS INFORMACIÓN", 0.9, salto3, { align: "left", maxWidth: "7.5" });   
                salto3 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto3, 7.7, salto3);

                salto3 += 0.3; 
                let inicio = salto3;
                doc
                    .setFontSize(12)
                    .text("FALTAS: " + this.nro_faltas, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("DÍAS COMPLETOS TRABAJADOS: " + this.nro_diasTrabajados, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("TOTAL RETRASOS: " + this.total_retraso, 1.1, salto3, { align: "left", maxWidth: "7.5" });   

                salto3 = inicio;
                doc
                    .setFontSize(12)
                    .text("PERMISOS: " + this.nro_permisos, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("ASISTENCIAS COMPLETAS: " + this.nro_asistencias, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 

                let index=1;
                this.itemsTodo[0].forEach( function(valor, indice, array) {
                    if(valor.estado==0)
                        valor.estado = "Incompleto";
                    if(valor.estado==1)
                        valor.estado = "Completo";
                    if(valor.estado==3)
                        valor.estado = "Permiso";
                    if(valor.h_entrada=="") valor.h_entrada = "--:--";
                    if(valor.h_salida=="") valor.h_salida = "--:--";
                    if(valor.retraso=="") valor.retraso = "--:--";
                    valor.asist_fecha =  moment( valor.asist_fecha ).format("DD/MM/YYYY")
                    valor.number = index;
                    index++
                });

                doc.addPage();

                doc.setFontSize(12).text("LISTA DE ASISTENCIAS", 0.9, 1.0);
                doc.autoTable({
                    columns: columns1,
                    body: this.itemsTodo[0],
                    margin: { left: 0.5, top: 1.2}
                });
                
                index=1;
                this.itemsTodo[1].forEach( function(valor, indice, array) {
                    if(valor.hr_entrada=="") valor.hr_entrada = "--:--";
                    
                    valor.hr_salida = "--:--";

                    valor.falta_fecha =  moment( valor.falta_fecha ).format("DD/MM/YYYY")

                    valor.number = index;
                    index++
                });
                
                var columns2 = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "falta_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "hr_entrada" },
                    { title: "Hr. salida", dataKey: "hr_salida" },
                    { title: "Turno", dataKey: "hd_nombre" },
                ];
                doc.addPage();

                doc.setFontSize(12).text("LISTA DE FALTAS", 0.9, 1.0);
                doc.autoTable({
                    columns: columns2,
                    body: this.itemsTodo[1],
                    margin: { left: 0.5, top: 1.2}
                });

                index=1;
                this.itemsTodo[2].forEach( function(valor, indice, array) {
                    valor.fecha_inicio =  moment( valor.fecha_inicio ).format("DD/MM/YYYY");
                    if(valor.fecha_final != "") valor.fecha_final =  moment( valor.fecha_final ).format("DD/MM/YYYY");
                    valor.number = index;
                    index++
                });

                var columns3 = [
                    { title: "#", dataKey: "number" },                    
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Fecha de inicio", dataKey: "fecha_inicio" },
                    { title: "Fecha final", dataKey: "fecha_final" },
                    { title: "Justificante", dataKey: "justificante" }
                ];

                doc.addPage();

                doc.setFontSize(12).text("LISTA DE PERMISOS", 0.9, 1.0);
                doc.autoTable({
                    columns: columns3,
                    body: this.itemsTodo[2],
                    margin: { left: 0.5, top: 1.2}
                });

                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save(`${this.practicante.nombre}.pdf`);
            },

            generatePdfPracticanteAsistencias() { 
                const columns = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "asist_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "h_entrada" },
                    { title: "Hr. salida", dataKey: "h_salida" },
                    { title: "Retraso", dataKey: "retraso" },
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Estado", dataKey: "estado" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);

                let pdfWidth = doc.internal.pageSize.getWidth()/2;

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.4;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.2;
                doc
                    .setFontSize(12)
                    .text("INFORMACIÓN DEL PRACTICANTE", 0.9, salto, { align: "left", maxWidth: "7.5" }); 
                salto += 0.1;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.3;
                let salto1 = salto;
                doc
                    .setFontSize(11)
                    .text("NOMBRE: " + this.practicante.nombre, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CI: " + this.practicante.ci, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CU: " + this.practicante.cu, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4;                 
                doc
                    .setFontSize(11)
                    .text("CARRERA: " + this.practicante.carrera, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4;    
                doc
                    .setFontSize(11)
                    .text("EMAIL: " +  this.practicante.email, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("TELÉFONO: " + this.practicante.telefono, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("DIRECCIÓN: " + this.practicante.direccion, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("FECHA INGRESO: " + this.fecha_formato(this.practicante.f_ingreso), 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                
                salto1 += 0.4; 
                //HorarioInfo
                doc.setLineWidth(0.01).line(0.8, salto1, 7.7, salto1);
                let salto2 = salto1 + 0.2;
                doc
                    .setFontSize(12)
                    .text("HORARIO: " + this.practicante.h_nombre, 0.9, salto2, { align: "left", maxWidth: "7.5" });   
                salto2 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);

                salto2 += 0.3;
                doc
                    .setFontSize(12)
                   .text("TURNOS:", 1.1, salto2, { align: "left", maxWidth: "7.5" });  

                this.horarios.forEach( function(valor, indice, array) {
                    doc
                        .setFontSize(12)
                        .text(valor.hd_nombre + "  =>  " + valor.horario_e + "  -  " + valor.horario_s, 2, salto2, { align: "left", maxWidth: "7.5" });  
                    salto2 += 0.4; 
                });
                    
                //MoreInfo
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);
                let salto3 = salto2 + 0.2;
                doc
                    .setFontSize(12)
                    .text("MÁS INFORMACIÓN", 0.9, salto3, { align: "left", maxWidth: "7.5" });   
                salto3 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto3, 7.7, salto3);

                salto3 += 0.3; 
                let inicio = salto3;
                doc
                    .setFontSize(12)
                    .text("FALTAS: " + this.nro_faltas, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("DÍAS COMPLETOS TRABAJADOS: " + this.nro_diasTrabajados, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("TOTAL RETRASOS: " + this.total_retraso, 1.1, salto3, { align: "left", maxWidth: "7.5" });   

                salto3 = inicio;
                doc
                    .setFontSize(12)
                    .text("PERMISOS: " + this.nro_permisos, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("ASISTENCIAS COMPLETAS: " + this.nro_asistencias, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 

                let index=1;
                this.items.forEach( function(valor, indice, array) {
                    if(valor.estado==0)
                        valor.estado = "Incompleto";
                    if(valor.estado==1)
                        valor.estado = "Completo";
                    if(valor.estado==3)
                        valor.estado = "Permiso";
                    if(valor.h_entrada=="") valor.h_entrada = "--:--";
                    if(valor.h_salida=="") valor.h_salida = "--:--";
                    if(valor.retraso=="") valor.retraso = "--:--";
                    valor.asist_fecha =  moment( valor.asist_fecha ).format("DD/MM/YYYY")
                    valor.number = index;
                    index++
                });

                doc.addPage();

                doc.setFontSize(12).text("LISTA DE ASISTENCIAS", 0.9, 1.0);
                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.2}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save(`Lista Asistencia - ${this.practicante.nombre}.pdf`);
            },
            
            generatePdfPracticanteFaltas() { 
                const columns = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "falta_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "hr_entrada" },
                    { title: "Hr. salida", dataKey: "hr_salida" },
                    { title: "Turno", dataKey: "hd_nombre" },
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });
                
                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);

                let pdfWidth = doc.internal.pageSize.getWidth()/2;

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.4;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.2;
                doc
                    .setFontSize(12)
                    .text("INFORMACIÓN DEL PRACTICANTE", 0.9, salto, { align: "left", maxWidth: "7.5" }); 
                salto += 0.1;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.3;
                let salto1 = salto;
                doc
                    .setFontSize(11)
                    .text("NOMBRE: " + this.practicante.nombre, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CI: " + this.practicante.ci, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CU: " + this.practicante.cu, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4;                 
                doc
                    .setFontSize(11)
                    .text("CARRERA: " + this.practicante.carrera, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4;    
                doc
                    .setFontSize(11)
                    .text("EMAIL: " +  this.practicante.email, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("TELÉFONO: " + this.practicante.telefono, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("DIRECCIÓN: " + this.practicante.direccion, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("FECHA INGRESO: " + this.fecha_formato(this.practicante.f_ingreso), 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                
                salto1 += 0.4; 
                //HorarioInfo
                doc.setLineWidth(0.01).line(0.8, salto1, 7.7, salto1);
                let salto2 = salto1 + 0.2;
                doc
                    .setFontSize(12)
                    .text("HORARIO: " + this.practicante.h_nombre, 0.9, salto2, { align: "left", maxWidth: "7.5" });   
                salto2 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);

                salto2 += 0.3;
                doc
                    .setFontSize(12)
                   .text("TURNOS:", 1.1, salto2, { align: "left", maxWidth: "7.5" });  

                this.horarios.forEach( function(valor, indice, array) {
                    doc
                        .setFontSize(12)
                        .text(valor.hd_nombre + "  =>  " + valor.horario_e + "  -  " + valor.horario_s, 2, salto2, { align: "left", maxWidth: "7.5" });  
                    salto2 += 0.4; 
                });
                    
                //MoreInfo
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);
                let salto3 = salto2 + 0.2;
                doc
                    .setFontSize(12)
                    .text("MÁS INFORMACIÓN", 0.9, salto3, { align: "left", maxWidth: "7.5" });   
                salto3 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto3, 7.7, salto3);

                salto3 += 0.3; 
                let inicio = salto3;
                doc
                    .setFontSize(12)
                    .text("FALTAS: " + this.nro_faltas, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("DÍAS COMPLETOS TRABAJADOS: " + this.nro_diasTrabajados, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("TOTAL RETRASOS: " + this.total_retraso, 1.1, salto3, { align: "left", maxWidth: "7.5" });   

                salto3 = inicio;
                doc
                    .setFontSize(12)
                    .text("PERMISOS: " + this.nro_permisos, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("ASISTENCIAS COMPLETAS: " + this.nro_asistencias, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 

                let index=1;
                this.items.forEach( function(valor, indice, array) {
                    if(valor.hr_entrada=="") valor.hr_entrada = "--:--";
                    
                    valor.hr_salida = "--:--";

                    valor.falta_fecha =  moment( valor.falta_fecha ).format("DD/MM/YYYY")

                    valor.number = index;
                    index++
                });

                doc.addPage();

                doc.setFontSize(12).text("LISTA DE FALTAS", 0.9, 1.0);
                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.2}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save(`Lista Faltas - ${this.practicante.nombre}.pdf`);
            },
            
            generatePdfPracticantePermisos() { 
                const columns = [
                    { title: "#", dataKey: "number" },                    
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Fecha de inicio", dataKey: "fecha_inicio" },
                    { title: "Fecha final", dataKey: "fecha_final" },
                    { title: "Justificante", dataKey: "justificante" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });
                
                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);


                let pdfWidth = doc.internal.pageSize.getWidth()/2;

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.4;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.2;
                doc
                    .setFontSize(12)
                    .text("INFORMACIÓN DEL PRACTICANTE", 0.9, salto, { align: "left", maxWidth: "7.5" }); 
                salto += 0.1;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);
                salto += 0.3;
                let salto1 = salto;
                doc
                    .setFontSize(11)
                    .text("NOMBRE: " + this.practicante.nombre, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CI: " + this.practicante.ci, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("CU: " + this.practicante.cu, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4;                 
                doc
                    .setFontSize(11)
                    .text("CARRERA: " + this.practicante.carrera, 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                salto1 += 0.4;    
                doc
                    .setFontSize(11)
                    .text("EMAIL: " +  this.practicante.email, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("TELÉFONO: " + this.practicante.telefono, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("DIRECCIÓN: " + this.practicante.direccion, 1.1, salto1, { align: "left", maxWidth: "7.5" });      
                salto1 += 0.4; 
                doc
                    .setFontSize(11)
                    .text("FECHA INGRESO: " + this.fecha_formato(this.practicante.f_ingreso), 1.1, salto1, { align: "left", maxWidth: "7.5" });   
                
                salto1 += 0.4; 
                //HorarioInfo
                doc.setLineWidth(0.01).line(0.8, salto1, 7.7, salto1);
                let salto2 = salto1 + 0.2;
                doc
                    .setFontSize(12)
                    .text("HORARIO: " + this.practicante.h_nombre, 0.9, salto2, { align: "left", maxWidth: "7.5" });   
                salto2 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);

                salto2 += 0.3;
                doc
                    .setFontSize(12)
                   .text("TURNOS:", 1.1, salto2, { align: "left", maxWidth: "7.5" });  

                this.horarios.forEach( function(valor, indice, array) {
                    doc
                        .setFontSize(12)
                        .text(valor.hd_nombre + "  =>  " + valor.horario_e + "  -  " + valor.horario_s, 2, salto2, { align: "left", maxWidth: "7.5" });  
                    salto2 += 0.4; 
                });
                    
                //MoreInfo
                doc.setLineWidth(0.01).line(0.8, salto2, 7.7, salto2);
                let salto3 = salto2 + 0.2;
                doc
                    .setFontSize(12)
                    .text("MÁS INFORMACIÓN", 0.9, salto3, { align: "left", maxWidth: "7.5" });   
                salto3 += 0.1; 
                doc.setLineWidth(0.01).line(0.8, salto3, 7.7, salto3);

                salto3 += 0.3; 
                let inicio = salto3;
                doc
                    .setFontSize(12)
                    .text("FALTAS: " + this.nro_faltas, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("DÍAS COMPLETOS TRABAJADOS: " + this.nro_diasTrabajados, 1.1, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("TOTAL RETRASOS: " + this.total_retraso, 1.1, salto3, { align: "left", maxWidth: "7.5" });   

                salto3 = inicio;
                doc
                    .setFontSize(12)
                    .text("PERMISOS: " + this.nro_permisos, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 
                doc
                    .setFontSize(12)
                    .text("ASISTENCIAS COMPLETAS: " + this.nro_asistencias, pdfWidth+0.3, salto3, { align: "left", maxWidth: "7.5" });      
                salto3 += 0.4; 

                let index=1;
                this.items.forEach( function(valor, indice, array) {
                    valor.fecha_inicio =  moment( valor.fecha_inicio ).format("DD/MM/YYYY");
                    if(valor.fecha_final != "") valor.fecha_final =  moment( valor.fecha_final ).format("DD/MM/YYYY");
                    valor.number = index;
                    index++
                });

                doc.addPage();

                doc.setFontSize(12).text("LISTA DE PERMISOS", 0.9, 1.0);
                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.2}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save(`Lista Permisos - ${this.practicante.nombre}.pdf`);
            },

            generatePdfAsistencias() { 
                const columns = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "asist_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "h_entrada" },
                    { title: "Hr. salida", dataKey: "h_salida" },
                    { title: "Retraso", dataKey: "retraso" },
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Estado", dataKey: "estado" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.2;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);

                doc.setFontSize(12).text("LISTA DE ASISTENCIAS", 0.9, 1.5);

                let index=1;
                this.items.forEach( function(valor, indice, array) {
                    if(valor.estado==0)
                        valor.estado = "Incompleto";
                    if(valor.estado==1)
                        valor.estado = "Completo";
                    if(valor.estado==3)
                        valor.estado = "Permiso";
                    if(valor.h_entrada=="") valor.h_entrada = "--:--";
                    if(valor.h_salida=="") valor.h_salida = "--:--";
                    if(valor.retraso=="") valor.retraso = "--:--";
                    valor.asist_fecha =  moment( valor.asist_fecha ).format("DD/MM/YYYY")
                    valor.number = index;
                    index++
                });

                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.6}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save("Lista Asistencias.pdf");
            },

            generatePdfFaltas() { 
                const columns = [
                    { title: "#", dataKey: "number" },
                    { title: "Fecha", dataKey: "falta_fecha" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Hr. entrada", dataKey: "hr_entrada" },
                    { title: "Hr. salida", dataKey: "hr_salida" },
                    { title: "Turno", dataKey: "hd_nombre" },
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.2;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);

                doc.setFontSize(12).text("LISTA DE FALTAS", 0.9, 1.5);

                let index=1;
                this.items.forEach( function(valor, indice, array) {
                    if(valor.hr_entrada=="") valor.hr_entrada = "--:--";
                    
                    valor.hr_salida = "--:--";

                    valor.falta_fecha =  moment( valor.falta_fecha ).format("DD/MM/YYYY")

                    valor.number = index;
                    index++
                });

                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.6}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save("Lista Faltas.pdf");
            },

            generatePdfPermisos() { 
                const columns = [
                    { title: "#", dataKey: "number" },                    
                    { title: "Turno", dataKey: "hd_nombre" },
                    { title: "Nombre", dataKey: "nombre" },
                    { title: "Fecha de inicio", dataKey: "fecha_inicio" },
                    { title: "Fecha final", dataKey: "fecha_final" },
                    { title: "Justificante", dataKey: "justificante" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_practicante) +" - "+ this.fecha_formato(this.form.f_date_practicante), 5.6, 0.55);

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.2;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);

                doc.setFontSize(12).text("LISTA DE PERMISOS", 0.9, 1.5);

                let index=1;
                this.items.forEach( function(valor, indice, array) {
                    valor.fecha_inicio =  moment( valor.fecha_inicio ).format("DD/MM/YYYY");
                    if(valor.fecha_final != "") valor.fecha_final =  moment( valor.fecha_final ).format("DD/MM/YYYY");
                    valor.number = index;
                    index++
                });

                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.6}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save("Lista Permisos.pdf");
            },

            generatePdfInactividad() { 
                const columns = [
                    { title: "Justificante", dataKey: "justificante" },
                    { title: "Fecha de inicio", dataKey: "fecha_inicio" },
                    { title: "Fecha final", dataKey: "fecha_final" }
                ];

                const doc = new jsPDF({
                    orientation: "portrait",
                    unit: "in",
                    format: "letter"
                });

                doc.setTextColor(128,128,128).setFontSize(10).text("UMRPSFXCH FACULTAD DE TECNOLOGÍA", 0.8, 0.55);

                doc.setTextColor(128,128,128).setFontSize(10).text("Informe: " + this.fecha_formato(this.form.i_date_inactividad) +" - "+ this.fecha_formato(this.form.f_date_inactividad), 5.6, 0.55);

                this.heading = "LABORATÓRIO DE ROBÓTICA - CONTROL DE ASISTENCIA";
                // text is placed using x, y coordinates
                doc.setTextColor(0,0,0).setFontSize(16).text(this.heading, 0.8, 1.0);


                // create a line under heading 
                let salto = 1.2;
                doc.setLineWidth(0.01).line(0.8, salto, 7.7, salto);

                doc.setFontSize(12).text("LISTA DE INACTIVIDADES", 0.9, 1.5);

                doc.autoTable({
                    columns,
                    body: this.items,
                    margin: { left: 0.5, top: 1.6}
                });
                
                const pageCount = doc.internal.getNumberOfPages();
                for (var i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(11).text('Página ' + String(i) + ' de ' + String(pageCount), 7.5, 10.5, {
                    align: 'center'
                    })
                }

                const today = new Date();
                const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const dateTime = date +'   '+ time;
                doc
                    .setFontSize(11)
                    .text(
                    dateTime,
                    0.5,
                    10.5
                    )
                    .save("Lista Inactividades.pdf");
            },

            showHide_p(visto) {      
                if (visto){                   
                    this.form.i_date_practicante = '';
                    this.form.f_date_practicante = '';
                }                
                this.visto_p = !this.visto_p;   
            },
            showHide_i(visto) {      
                if (visto){                   
                    this.form.i_date_inactividad = '';
                    this.form.f_date_inactividad = '';
                }                
                this.visto_i = !this.visto_i;   
            },
            showHide_Form_p(visto) {   
                this.visto_i = true,           
                this.visto_form_p = true;   
                this.visto_form_i = false;   
                this.form.flag_p = true;
                this.form.flag_i = "";
            },
            showHide_Form_i(visto) {  
                this.visto_p = true,            
                this.visto_form_p = false;   
                this.visto_form_i = true;  
                this.form.flag_p = "";
                this.form.flag_i = true; 
            }, 
            
            totalRetraso(){
                axios.post('api/total-retraso-pdf',this.form)
                    .then(res=>{
                        this.total_retraso = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            nroDiasTrabajados(){
                axios.post('api/dias-trabajo-nro-pdf',this.form)
                    .then(res=>{
                        this.nro_diasTrabajados = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            nroAsistencias(){
                axios.post('api/asistencias-nro-pdf',this.form)
                    .then(res=>{
                        this.nro_asistencias = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },            
            nroFaltas(){
                axios.post('api/faltas-nro-pdf',this.form)
                    .then(res=>{
                        this.nro_faltas = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },            
            nroPermisos(){
                axios.post('api/permisos-nro-pdf',this.form)
                    .then(res=>{
                        this.nro_permisos = res.data;
                        //console.log(res.data)
                    }).catch(error=>{console.log(error.response.data)});
            },
            practicanteInfo(){
                let id = this.form.practicante_id;
                axios.get('api/practicante-info/'+id)
                    .then(res=>{
                        this.practicante = res.data[0];
                        this.horarios = res.data[1];
                    }).catch(error=>{console.log(error.response.data)});
            },
            fecha_formato : function( date ) {
                if(date!="") return moment( date ).format("DD/MM/YYYY");
                else "";
            },
            change(){
                this.practicanteInfo();
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
