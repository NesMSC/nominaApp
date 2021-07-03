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
                <li class="breadcrumb-item"><a href="#">Acceso</a></li>
                <li class="breadcrumb-item active">Roles</li>
              </ol>
            </template>
            <template v-if="accion=='registrar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Roles</a></li>
                <li class="breadcrumb-item">Agregar</li>
              </ol>
            </template>
            <template v-if="accion=='ver'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Roles</a></li>
                <li class="breadcrumb-item">Consultar</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Roles</a></li>
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
                      <input @keyup="listarRoles(pagination.current_page, busqueda)" v-model="busqueda" id="search" type="text" class="form-control" placeholder="Busqueda">
                    </div>
                  </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="rol in arrayRoles" :key="rol.id">
                      <td v-text="rol.nombre"></td>
                      <td v-text="rol.descripcion"></td>
                      <td v-text="(rol.estado)?'Activo':'Inactivo'"></td>
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
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</template>
<script>
    export default {
        data() {
          return {
            nombre: "",
            descripcion: "",
            condicion: 1,    
            arrayRoles: [],
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
            criterio:""
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
          listarRoles(page, busqueda){
            let me = this;
            var url= '/roles?page='+page+'&busqueda='+busqueda;
            axios.get(url).then(function (response) {
                var respuesta= response.data.roles.data;
                me.arrayRoles = respuesta;
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
          inhabilitarRol(id){
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
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarDescuentos(page, this.busqueda);
          }   
        }, 
        mounted() {
          this.listarRoles(1, this.busqueda);
        }
    }
</script>