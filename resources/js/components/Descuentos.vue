<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <template v-if="accion=='listar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Salarios</a></li>
                <li class="breadcrumb-item active">Descuentos</li>
              </ol>
            </template>
            <template v-if="accion=='registrar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Descuentos</a></li>
                <li class="breadcrumb-item">Agregar</li>
              </ol>
            </template>
            <template v-if="accion=='ver'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Descuentos</a></li>
                <li class="breadcrumb-item">Consultar</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Descuentos</a></li>
                <li class="breadcrumb-item">Editar</li>
              </ol>
            </template>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid" v-if="accion=='listar'">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button @click="accion='registrar'; resetearInputs();" type="button" class="btn btn-light">
                  <i class="fa fa-plus"></i>&nbsp;Nuevo
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <a href="#" ><i aria-hidden="true" style="color:#000;" class='fa fa-search'></i></a>
                        </div>
                      </div>
                      <input @keyup="listarDescuentos(pagination.current_page, busqueda, criterio)" v-model="busqueda" id="search" type="text" class="form-control" placeholder="Busqueda">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <select @change="listarDescuentos(1, busqueda, criterio)" class="form-control" v-model="criterio">
                        <option value="" selected>Todos</option>
                        <option value="Retención">Retención</option>
                        <option value="Aporte">Aporte</option>
                        <option value="Descuento">Descuento</option>
                      </select>
                    </div>
                  </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Concepto</th>
                    <th>Tipo</th>
                    <th>Porcentaje</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="descuento in arrayDescuentos" :key="descuento.id">
                      <td v-text="descuento.concepto"></td>
                      <td v-text="descuento.tipo"></td>
                      <td v-text="descuento.porcentaje+'%'"></td>
                      <td>
                        <a href="#" style="color:#000;" @click.prevent="accion='ver'; consultarDescuento(descuento.id)"><i class="far fa-eye" ></i></a>
                        <a href="#" @click.prevent="accion='editar'; editarDescuento(descuento.id)"><i class="fas fa-edit"></i></a>
                        <a href="#" style="color:red;" @click.prevent="eliminarDescuento(descuento.id)"><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
                
                  <ul class="pagination btn-group mr-2 mt-4" role="group" aria-label="First group">
                    <li v-if="pagination.current_page > 1">
                      <button type="button" class="btn btn-light" @click.prevent="cambioPagina(pagination.current_page - 1)">Atras</button>
                    </li>
                    <li v-for="page in pageNumber" :key="page" :class="[page == 1 ? 'active' : '']">
                      <button @click.prevent="cambioPagina(page)" :class="[page == isActive ? 'btn-primary' : 'btn-light']" v-text="page" type="button" class="btn"></button>
                    </li>
                    <li v-if="pagination.current_page < pagination.last_page">
                      <button type="button" class="btn btn-light" @click.prevent="cambioPagina(pagination.current_page + 1)">Siguiente</button>
                    </li>
                  </ul>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      <div class="container-fluid" v-if="accion=='registrar' || accion=='editar'">
        <!-- Formulario de registro de -->
          <!-- card datos Deducción -->      
          <div class="card card-default">
            <div class="card-header">
              <h3 v-if="accion=='registrar'" class="card-title">Agregar Deducción</h3>
              <h3 v-else class="card-title">Editar Deducción</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div> 
            <!-- /.card-header -->
            <div class="card-body"> 
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="concepto">Concepto</label>
                  <input v-model="concepto" @change="validarCampo(concepto, 'concepto')" type="text" class="form-control" id="concepto" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                
                <div class="col-md-3 mb-2 form-group">
                  <label for="tipo">Tipo</label>
                  <select v-model="tipo" @change="validarCampo(tipo, 'tipo')" id="tipo" class="form-control" required>
                    <option selected disabled>Seleccionar</option>
                    <option value="Retención">Retención</option>
                    <option value="Aporte">Aporte</option>
                    <option value="Descuento">Descuento</option>
                  </select>
                  <div class="invalid-feedback">
                        *Este campo es requerido
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2 mb-2 form-group">
                  <label for="porcentaje">Porcentaje de sueldo</label>
                  <input v-model="porcentaje" @change="validarCampo(porcentaje, 'porcentaje')" type="number" class="form-control" id="porcentaje" required>
                  <div class="invalid-feedback">
                          *Campo inválido
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            </div>
          </div>
          <!-- /.card -->
          <template v-if="accion=='registrar'">
            <button @click="registrar()" type="button" class="btn btn-primary btn-lg">Registrar</button>
          </template>
          <template v-if="accion=='editar'">
            <button @click="actualizarDescuento()" type="button" class="btn btn-primary btn-lg">Actualizar</button>
          </template>
          
      </div><!-- /.container-fluid -->
      <div class="container-fluid" v-if="accion=='ver'">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <strong><p v-text="concepto"></p></strong>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table1" class="table">
                  <tbody>
                    <tr >
                      <td v-text="'Tipo: '+tipo"></td>
                    </tr>
                    <tr>
                      <td v-text="'Descontado: '+porcentaje+'% del salario'"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</template>
<script>
    export default {
        data() {
          return {
            concepto: "",
            tipo: "Seleccionar",
            porcentaje: 0,    
            arrayDescuentos: [],
            pagination: {
                "total": 0,
                "current_page": 0,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
              },
            accion: 'listar', 
            error: [],
            busqueda: "",
            criterio:"",
            id_descuento: ""
          }
        },
        computed:{
          
          isActive: function (){
            return this.pagination.current_page;
          },
          pageNumber: function(){

            let me = this;

            if(!me.pagination.to){
              return [];
            }

            var from = me.pagination.current_page - 2; //TODO offset

            if (from < 1) {
              from = 1;
            };

            var to = from + (2 * 2); //TODO

            if (to >= me.pagination.last_page) {
              to = me.pagination.last_page;
            };

            var pagesArray = [];

            while(from <= to){
              pagesArray.push(from);
              from++;
            };

            return pagesArray;
          }
        },
        methods: {
          listarDescuentos(page, busqueda, criterio){
            let me = this;
            var url= '/descuentos?page='+page+'&busqueda='+busqueda+'&criterio='+criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data.descuentos.data;
                me.arrayDescuentos = respuesta;
                me.pagination = response.data.pagination;
            }).catch(function (error) {
                console.log(error);
            });
          },
          registrar(){
            let me = this;
            let url = '/descuentos/agregarNuevo';
            if(me.validarForm()){
              axios.post(url, {
                concepto: me.concepto,
                tipo: me.tipo,
                porcentaje: me.porcentaje
              }).then(function (response){
                swal.fire(
                        'Deducción agregado exitosamente',
                        '',
                        'success'
                      )
                me.accion = "listar";
                me.listarDescuentos(1, me.busqueda, me.criterio);
                me.resetearInputs();
              }).catch(function (error){
                console.log(error)
              });
            };
          },
          consultarDescuento(id){
            let me = this;
            let url = '/descuentos/show/'+id;
            axios.get(url).then(function (response){
              let descuento = response.data.descuento;
              me.concepto = descuento.concepto;
              me.tipo = descuento.tipo;
              me.porcentaje = descuento.porcentaje;
            }).catch(function (error){
              console.log(error);
            });
          },
          editarDescuento(id){
            let me = this;
            let url = '/descuentos/editar/'+id;

            axios.get(url).then(function(response){
              let descuento = response.data.descuento;
              me.concepto = descuento.concepto;
              me.tipo = descuento.tipo;
              me.porcentaje = descuento.porcentaje;
              me.id_descuento = descuento.id;
            }).catch(function(error){
              console.log(error);
            });
          },
          actualizarDescuento(){
            let me = this;
            let url = '/descuentos/actualizar';
            if (me.validarForm()) {
              axios.put(url,{
                concepto:me.concepto,
                tipo:me.tipo,
                porcentaje:me.porcentaje,
                id:me.id_descuento
              }).then(function(response){
                swal.fire(
                        'Actualizado exitosamente',
                        '',
                        'success');
                me.accion = "listar";
                me.listarDescuentos(1, me.busqueda, me.criterio);
                me.resetearInputs();
              }).catch(function(error){
                console.log(error);
              });
            };            
          },
          eliminarDescuento(id){
            let me = this;
            let url = '/descuentos/delete/'+id;
            swal.fire({
              title: '¿Seguro que desea eliminar?',
              text: "",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continuar',
              cancelButtonText: 'Cancelar'
            }).then((result)=>{
              if (result.value) {
                axios.delete(url).then(function(response){
                  swal.fire(
                        'Descuento eliminado',
                        '',
                        'success');
                  me.accion = "listar";
                  me.listarDescuentos(1, me.busqueda, me.criterio);
                  me.resetearInputs();
                }).catch(function(error){
                  console.log(error);
                });
              }else return;
            });   
          },
          validarForm(){
            const inputs = document.getElementsByClassName('form-control');
            //Validar todos los campos vacios

              for (let i = 0; i < inputs.length; i++) {               
                const e = inputs[i];
                if(!e.value || e.value == "Seleccionar" || e.value == 0){
                  e.classList.add('is-invalid');
                  if (!this.error.includes(e.id)) {
                    console.log(e.id);
                    this.error.push(e.id);
                  };
                }else{
                  e.classList.remove('is-invalid');
                  e.classList.add('is-valid');
                  let indiceElement = this.error.indexOf(e.id);
                  //Verifica si existe el indice
                  if (indiceElement!== -1) {
                    this.error.splice(indiceElement, 1);
                  };
                };
                this.validarCampo(e.value, e.id);
              };

           if(!this.error.length){return true}
          },
          validarCampo(campo, id){
            let element = document.getElementById(id);
            
            if(id=='porcentaje'){
              if (parseInt(campo) && campo > 0 && campo < 100) {
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
                let indiceElement = this.error.indexOf(element.id);
                //Verifica si existe el indice
                if (indiceElement!= -1) {
                  this.error.splice(indiceElement, 1);
                };
              }else{
                element.classList.add('is-invalid');
                if (!this.error.includes(element.id)) {
                  this.error.push(element.id);
                };
              };
            }else{
              if (campo!= '' && campo!='Seleccionar') {
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
                let indiceElement = this.error.indexOf(element.id);
                //Verifica si existe el indice
                if (indiceElement!= -1) {
                  this.error.splice(indiceElement, 1);
                };
              }else{
                element.classList.add('is-invalid');
                if (!this.error.includes(element.id)) {
                  this.error.push(element.id);
                };
              };
            };   
          },
          resetearInputs(){
            this.concepto = "";
            this.tipo = "Seleccionar";
            this.porcentaje = 0;
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarDescuentos(page, this.busqueda, this.criterio);
          }   
        }, 
        mounted() {
          this.listarDescuentos(1, this.busqueda, this.criterio);
        }
    }
</script>