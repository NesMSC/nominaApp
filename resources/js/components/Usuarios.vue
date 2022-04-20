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
                <li class="breadcrumb-item active">Usuarios</li>
              </ol>
            </template>
            <template v-if="accion=='registrar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Usuarios</a></li>
                <li class="breadcrumb-item">Registrar</li>
              </ol>
            </template>
            <template v-if="accion=='ver'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Usuarios</a></li>
                <li class="breadcrumb-item">Consultar</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Usuarios</a></li>
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
                  <i class="fa fa-plus"></i>&nbsp;Registrar nuevo
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
                      <input @keyup="listarUsuarios(pagination.current_page, busqueda, criterio)" v-model="busqueda" id="search" type="text" class="form-control" placeholder="Busqueda">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <select @change="listarUsuarios(1, busqueda, criterio)" class="form-control" v-model="criterio">
                        <option value="" selected>Todos</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Apoyo">Apoyo</option>
                        <option value="hab">Habilitados</option>
                        <option value="inhab">Inhabilitados</option>
                      </select>
                    </div>
                  </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="usuario in arrayUsuarios" :key="usuario.id">
                      <td v-text="usuario.name"></td>
                      <td v-text="usuario.rol"></td>
                      <td>
                        <template v-if="usuario.condicion">
                          <span class="text-success">Habilitado</span>
                        </template>
                        <template v-else>
                          <span class="text-danger">Deshabilitado</span>
                        </template>
                      </td>
                      <td>
                        <a href="#" @click.prevent="accion='editar'; mostrarUsuario(usuario.id)"><i class="fas fa-edit"></i></a>
                        <template v-if="usuario.condicion">
                          <a href="#" @click.prevent="cambiarEstadoUser(usuario.id, usuario.rol, 0)"><i style="color:red;" class="fa fa-ban" aria-hidden="true"></i></a>
                        </template>
                        <template v-else>
                          <a href="#" @click.prevent="cambiarEstadoUser(usuario.id, usuario.rol, 1)"><i style="color:green;" class="fa fa-check-circle" aria-hidden="true"></i></a>
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
          <!-- card datos usuario -->      
          <div class="card card-default">
            <div class="card-header">
              <h3 v-if="accion=='registrar'" class="card-title">Registrar usuario</h3>
              <h3 v-else class="card-title">Editar usuario</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 form-group">
                  <template v-if="accion=='registrar'">
                    <v-select
                   @search="buscarPerson"
                   :options="arrayPerson"
                   label="person"
                   :reduce="person => person.id"
                   :placeholder="'Buscar personal...'"
                   v-model="id_persona"
                   ></v-select>
                  </template>
                  <template>
                    <p v-text="`${nombre} ${apellido} ${cedula}`"></p>
                  </template>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="usuario">Usuario</label>
                  <input v-model="usuario" @change="validarCampo(usuario, 'usuario')" type="text" class="form-control" id="usuario" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="password">Contraseña</label>
                  <template v-if="mostrarPassword">
                    <input v-model="password" @change="validarCampo(password, 'password')" type="text" class="form-control" id="password" required>
                  </template>
                  <template v-else>
                    <input v-model="password" @change="validarCampo(password, 'password')" type="password" class="form-control" id="password" required>
                  </template>
                  
                  <div class="invalid-feedback">
                          *Este campo es inválido
                  </div>
                </div>
                <div class="col-md-2 mt-4 ml-2 form-group">
                  <a href="#" class="btn btn-light" @click.prevent="mostrarPassword=mostrarPassword?false:true"><i :class="mostrarPassword?'fa fa-eye-slash':'far fa-eye'"></i></a>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-2 form-group">
                  <label for="rol">Rol</label>
                  <select v-model="rol" @change="validarCampo(rol, 'rol')" id="rol" class="form-control" required>
                    <option selected disabled>Seleccionar</option>
                    <option value="1">Administrador</option>
                    <option value="2">Apoyo</option>
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
          <template v-if="accion=='editar'">
            <button @click="actualizarUsuario()" type="button" class="btn btn-primary btn-lg">Actualizar</button>
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
            usuario: "",
            password: "",
            mostrarPassword:false,
            rol: "Seleccionar",
            estado: 1,    
            arrayUsuarios: [],
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
            arrayPerson: [],
            id_persona: "",
            nombre: "",
            apellido: "",
            cedula: "",
            user_id:""
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
          listarUsuarios(page, busqueda, criterio){
            let me = this;
            var url= '/usuarios?page='+page+'&busqueda='+busqueda+'&criterio='+criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data.usuarios.data;
                me.arrayUsuarios = respuesta;
                me.pagination = response.data.pagination;
            }).catch(function (error) {
                console.log(error);
            });
          },
          buscarPerson(search, loading){
            let me=this;
            loading(true)
              var url= '/usuarios/buscarPerson?filtro='+search;
              //Consultar todo el personal
              axios.get(url).then(function (response) {
                  var respuesta= response.data.personas;
                  me.arrayPerson=[];
                  for(var i = 0; i < respuesta.length; i++){
                    me.arrayPerson.push({person:respuesta[i].nombres +" "+ respuesta[i].apellidos +" "+ respuesta[i].cedula, id:respuesta[i].id});
                  };
                loading(false);
              })
              .catch(function (error) {
                  console.log(error);
            });  
          },
          registrar(){
            let me = this;
            let url = '/usuarios/agregarNuevo';
            if(me.validarForm()){
              axios.post(url, {
                usuario: me.usuario,
                rol: me.rol,
                contraseña: me.password,
                id_persona:me.id_persona
              }).then(function (response){
                swal.fire(
                        'Usuario registrado exitosamente',
                        '',
                        'success'
                      )
                me.accion = "listar";
                me.listarUsuarios(1, me.busqueda, me.criterio);
                me.resetearInputs();
              }).catch(function (error){
                console.log(error)
              });
            };
          },
          mostrarUsuario(id){
            let me = this;
            let url = '/usuarios/editar/'+id;

            axios.get(url).then(function(response){
              let usuario = response.data.usuario;
              me.usuario = usuario.name;
              me.rol = usuario.rol;
              me.password = "";
              me.nombre = usuario.nombres;
              me.apellido = usuario.apellidos;
              me.cedula = usuario.cedula;
              me.user_id = usuario.id
            }).catch(function(error){
              console.log(error);
            });
          },
          actualizarUsuario(){
            let me = this;
            let url = '/usuarios/actualizar';
            if (me.validarForm()) {
              axios.put(url,{
                usuario:me.usuario,
                rol:me.rol,
                password:me.password,
                user_id:me.user_id
              }).then(function(response){
                swal.fire(
                        'Actualizado exitosamente',
                        '',
                        'success');
                me.accion = "listar";
                me.listarUsuarios(1, me.busqueda, me.criterio);
                me.resetearInputs();
              }).catch(function(error){
                console.log(error);
              });
            };            
          },
          cambiarEstadoUser(id, rol, estado){
            let me = this;
            let url = '/usuarios/cambiarEstado';
            swal.fire({
              title: estado?'¿Desea habilitar este usuario?':'¿Desea deshabilitar este usuario?',
              text: "",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continuar',
              cancelButtonText: 'Cancelar'
            }).then((result)=>{
              if (result.value) {
                axios.put(url,{user_id:id, condicion:estado, rol:rol})
                .then(function(response){
                  if(response.data.status){
                    swal.fire(
                        estado?'Usuario habilitado':'Usuario deshabilitado',
                        '',
                        'success');
                  }else{
                    swal.fire(
                        response.data.msg,
                        '',
                        'error');
                  }
                  
                  me.accion = "listar";
                  me.listarUsuarios(1, me.busqueda, me.criterio);
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
            
            if(id=='password'){
              if (campo.length > 8) {
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
                Vue.toasted.error( 'La contraseña debe tener al menos 8 digitos', {duration:2000});
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
            this.usuario = "";
            this.contraseña = "";
            this.rol = "Seleccionar";
            this.id_persona = ""
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarUsuarios(page, this.busqueda);
          }   
        }, 
        mounted() {
          this.listarUsuarios(1, this.busqueda, this.criterio);
        }
    }
</script>