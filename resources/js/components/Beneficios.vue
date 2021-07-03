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
                <li class="breadcrumb-item active">Beneficios</li>
              </ol>
            </template>
            <template v-if="accion=='registrar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Beneficios</a></li>
                <li class="breadcrumb-item">Agregar</li>
              </ol>
            </template>
            <template v-if="accion=='ver'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Beneficios</a></li>
                <li class="breadcrumb-item">Consultar</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Beneficios</a></li>
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
                      <input @keyup="listarBeneficios(pagination.current_page, busqueda)" v-model="busqueda" id="search" type="text" class="form-control" placeholder="Busqueda">
                    </div>
                  </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Concepto</th>
                    <th>Valor</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="beneficio in arrayBeneficios" :key="beneficio.id">
                      <td v-text="beneficio.concepto"></td>
                      <td v-text="beneficio.valor+' '+beneficio.tipo_valor"></td>
                      <td>
                        <a href="#" style="color:#000;" @click.prevent="accion='ver'; consultarBeneficio(beneficio.id)"><i class="far fa-eye" ></i></a>
                        <template v-if="beneficio.concepto=='Prima de Profesionalización'">
                          <a href="#" @click.prevent="accion='editar'; editarPrimaProfesional(beneficio.id)"><i class="fas fa-edit"></i></a>
                        </template>
                        <template v-else>
                          <a href="#" @click.prevent="accion='editar'; editarBeneficio(beneficio.id)"><i class="fas fa-edit"></i></a>
                        </template>
                        
                        <template v-if="beneficio.concepto!='Prima de Profesionalización' && beneficio.concepto!='Prima de Antiguedad'">
                          <a href="#" style="color:red;" @click.prevent="eliminarBeneficio(beneficio.id)"><i class="fas fa-trash"></i></a>
                        </template>
                        
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
          <!-- card datos Beneficio -->      
          <div class="card card-default">
            <div class="card-header">
              <h3 v-if="accion=='registrar'" class="card-title">Agregar Beneficio</h3>
              <h3 v-else class="card-title">Editar Beneficio</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">
              <div v-if="concepto=='Prima de Profesionalización'">
                <div class="row mb-4">
                  <div class="col-md-4">
                    <label for="concepto">Concepto</label>
                    <input v-model="concepto" type="text" class="form-control" disabled id="concepto" required>
                  </div>
                  <div class="col-md-2">
                    <label for="tipo_valor">Tipo de valor</label>
                    <select id="tipo_valor" name="tipo_valor" class="form-control" required>
                      <option value="%" disabled selected>%</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <form>
                    <div class="form-group row">
                      <label for="tsu" class="col-sm-4 col-form-label col-form-label-sm">TSU</label>
                      <div class="col-sm-4">
                        <input @change="validarPrimaPro(primaProfesional.TSU, 'tsu')" v-model="primaProfesional.TSU" type="number" class="form-control form-control-sm" id="tsu">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="profesional" class="col-sm-4 col-form-label col-form-label-sm">Profesional</label>
                      <div class="col-sm-4">
                        <input @change="validarPrimaPro(primaProfesional.Profesional, 'profesional')" v-model="primaProfesional.Profesional" type="number" class="form-control form-control-sm" id="profesional">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="especialista" class="col-sm-4 col-form-label col-form-label-sm">Especialista</label>
                      <div class="col-sm-4">
                        <input @change="validarPrimaPro(primaProfesional.Especialista, 'especialista')" v-model="primaProfesional.Especialista" type="number" class="form-control form-control-sm" id="especialista">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="maestria" class="col-sm-4 col-form-label col-form-label-sm">Maestria</label>
                      <div class="col-sm-4">
                        <input @change="validarPrimaPro(primaProfesional.Maestria, 'maestria')" v-model="primaProfesional.Maestria" type="number" class="form-control form-control-sm" id="maestria">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="doctor" class="col-sm-4 col-form-label col-form-label-sm">Doctor</label>
                      <div class="col-sm-4">
                        <input @change="validarPrimaPro(primaProfesional.Doctor, 'doctor')" v-model="primaProfesional.Doctor" type="number" class="form-control form-control-sm" id="doctor">
                      </div>
                    </div>
                  </form>
                </div>                 
              </div>  
              <div v-else class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="concepto">Concepto</label>
                  <input v-if="concepto == 'Prima de Antiguedad'" v-model="concepto" type="text" class="form-control" disabled id="concepto" required>
                  <input v-else v-model="concepto" type="text" class="form-control" id="concepto" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-3 mb-2 form-group">
                  <label for="valor">valor</label>
                  <input v-model="valor" type="number" class="form-control" id="valor" required>
                  <div class="invalid-feedback">
                          *Campo inválido
                  </div>
                </div>
                <div class="col-md-3 mb-2 form-group">
                  <label for="tipo_valor">Tipo de valor</label>
                  <select v-model="tipo_valor" id="tipo_valor" name="tipo_valor" class="form-control" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="U.T">U.T</option>
                    <option value="%">%</option>
                  </select>
                  <div class="invalid-feedback">
                        *Este campo es requerido
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
          <template v-if="accion=='editar' && concepto != 'Prima de Profesionalización'">
            <button @click="actualizarBeneficio()" type="button" class="btn btn-primary btn-lg">Actualizar</button>
          </template>
          <template v-if="accion=='editar' && concepto == 'Prima de Profesionalización'">
            <button @click="actualizarPrimaProfesional()" type="button" class="btn btn-primary btn-lg">Actualizar</button>
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
                      <td v-text="'Valor: '+valor+tipo_valor"></td>
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
      <!-- Button trigger modal -->
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
            valor: 0.0,
            tipo_valor: "Seleccionar",    
            arrayBeneficios: [],
            pagination: {
                "total": 0,
                "current_page": 0,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
              },
            primaProfesional: {
              "TSU":0,
              "Profesional":0,
              "Especialista":0,
              "Maestria":0,
              "Doctor":0
            },
            accion: 'listar',
            UT: '', 
            error: [],
            errorsPorPrima: [],
            busqueda: "",
            id_beneficio: ""
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
          listarBeneficios(page, busqueda){
            let me = this;
            var url= '/beneficios?page='+page+'&busqueda='+busqueda;
            axios.get(url).then(function (response) {
                var respuesta= response.data.beneficios.data;
                me.arrayBeneficios = respuesta;
                me.pagination = response.data.pagination;
            }).catch(function (error) {
                console.log(error);
            });
          },
          registrar(){
            let me = this;
            let url = '/beneficios/agregarNuevo';
            if(me.validarForm()){
              axios.post(url, {
                concepto: me.concepto,
                valor: me.valor,
                tipo_valor: me.tipo_valor
              }).then(function (response){
                swal.fire(
                        'Beneficio agregado exitosamente',
                        '',
                        'success'
                      )
                me.accion = "listar";
                me.listarBeneficios(1, me.busqueda);
                me.resetearInputs();
              }).catch(function (error){
                console.log(error)
              });
            };
          },
          consultarBeneficio(id){
            let me = this;
            let url = '/beneficios/show/'+id;
            axios.get(url).then(function (response){
              let beneficio = response.data.beneficio;
              me.concepto = beneficio.concepto;
              me.valor = beneficio.valor;
              me.tipo_valor = beneficio.tipo_valor;
            }).catch(function (error){
              console.log(error);
            });
          },
          editarBeneficio(id){
            let me = this;
            let url = '/beneficios/editar/'+id;

            axios.get(url).then(function(response){
              let beneficio = response.data.beneficio;
              me.concepto = beneficio.concepto;
              me.valor = beneficio.valor;
              me.tipo_valor = beneficio.tipo_valor;
              me.id_beneficio = beneficio.id;
            }).catch(function(error){
              console.log(error);
            });
          },
          actualizarBeneficio(){
            let me = this;
            let url = '/beneficios/actualizar';
            if (me.validarForm()) {
              axios.put(url,{
                concepto:me.concepto,
                valor:me.valor,
                tipo_valor:me.tipo_valor,
                id:me.id_beneficio
              }).then(function(response){
                swal.fire(
                        'Actualizado exitosamente',
                        '',
                        'success');
                me.accion = "listar";
                me.listarBeneficios(1, me.busqueda, me.criterio);
                me.resetearInputs();
              }).catch(function(error){
                console.log(error);
              });
            };
            
          },
          editarPrimaProfesional(){
            let me = this;
            me.concepto = 'Prima de Profesionalización';
            me.tipo_valor = '%';
            let url = '/beneficios/primaProfesional';

            axios.get(url).then(function(response){
              me.primaProfesional = response.data.primaProfesional;
            }).catch(function(error){
              console.log(error);
            })
          },
          actualizarPrimaProfesional(){
            let me = this;
            let url = '/beneficios/primaProfesional/update';
            if (me.validarForm()) {
              axios.put(url, {json_prima:me.primaProfesional}).then(function(response){
                swal.fire(
                        'Actualizado exitosamente',
                        '',
                        'success');
                me.accion = "listar";
                me.listarBeneficios(1, me.busqueda, me.criterio);
                me.resetearInputs();
              }).catch(function(error){
                console.log(error);
              });
            }
          },
          eliminarBeneficio(id){
            let me = this;
            let url = '/beneficios/delete/'+id;
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
                        'Beneficio eliminado',
                        '',
                        'success');
                  me.accion = "listar";
                  me.listarBeneficios(1, me.busqueda, me.criterio);
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
                if (e.id!='tsu' && e.id!='profesional' && e.id!='especialista' && e.id!='maestria' && e.id!='doctor') {
                  if(!e.value || e.value == "Seleccionar" || e.value == 0){
                    e.classList.add('is-invalid');
                    if (!this.error.includes(e.id)) {
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
                };
              };

           if(!this.error.length && !this.errorsPorPrima.length){return true}
          },
          validarPrimaPro(campo, id){
            let element = document.getElementById(id);
            
            if (parseInt(campo) && campo > 0 && campo < 100) {
              element.classList.remove('is-invalid');
              element.classList.add('is-valid');
              let indiceElement = this.errorsPorPrima.indexOf(element.id);
              //Verifica si existe el indice
              if (indiceElement!= -1) {
                this.errorsPorPrima.splice(indiceElement, 1);
              };
            }else{
              element.classList.add('is-invalid');
              if (!this.errorsPorPrima.includes(element.id)) {
                this.errorsPorPrima.push(element.id);
              };
            };
          },
          resetearInputs(){
            this.concepto = "";
            this.valor = 0;
            this.tipo_valor = "Seleccionar";
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarBeneficios(page, this.busqueda);
          }   
        }, 
        mounted() {
          this.listarBeneficios(1, this.busqueda);
        }
    }
</script>