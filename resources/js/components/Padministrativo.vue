<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">      
            <template v-if="accion=='listar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trabajadores</a></li>
                <li class="breadcrumb-item active">Administrativo</li>
              </ol>
            </template>
            <template v-if="accion=='registrar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Administrativo</a></li>
                <li class="breadcrumb-item">Registrar</li>
              </ol>
            </template> 
            <template v-if="accion=='aggDatosSalarios'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Administrativo</a></li>
                <li v-if="id_empleado==''" class="breadcrumb-item" ><a href="#" @click.prevent="accion='registrar';">Registrar</a></li>
                <li v-else class="breadcrumb-item" ><a href="#" 
                @click.prevent="accion='editar';">Editar</a></li>
                <li class="breadcrumb-item">Agregar datos salariales</li>

              </ol>
            </template>
            <template v-if="accion=='ver'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Administrativo</a></li>
                <li class="breadcrumb-item">Consultar</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Administrativo</a></li>
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
                          <a href="#" ><i aria-hidden="true" class='fa fa-search'></i></a>
                        </div>
                      </div>
                      <input @keyup="listarEmpleado(pagination.current_page, busqueda, criterio)" v-model="busqueda" id="search" type="text" class="form-control" placeholder="Busqueda">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <select @change="listarEmpleado(1, busqueda, criterio)" class="form-control" v-model="criterio">
                        <option value="" selected>Todos</option>
                        <option value="Fijo">Fijos</option>
                        <option value="Contratado">Contratados</option>
                        <option value="Pensionado">Pensionados</option>
                        <option value="Jubilado">Jubilados</option>
                        <option value="trashed">Papelera</option>
                      </select>
                    </div>
                  </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cargo</th>
                    <th>Departamento</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="empleado in arrayempleado" :key="empleado.id">
                      <td v-text="empleado.nombres"></td>
                      <td v-text="empleado.apellidos"></td>
                      <td v-text="empleado.grado+' '+empleado.nivel"></td>
                      <td v-text="empleado.departamento"></td>

                      <td v-if="criterio != 'trashed'">
                        <a href="#" style="color:#fff;" class="btn btn-info btn-sm" @click.prevent="accion='ver'; editarEmpleado(empleado.id)"><i class="far fa-eye" ></i></a>
                        <a href="#" style="color:#fff;" class="btn btn-success btn-sm" @click.prevent="accion='editar'; editarEmpleado(empleado.id)"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-danger btn-sm text-light" @click.prevent="eliminarEmpleado(empleado.id, `${empleado.nombres} ${empleado.apellidos}`)"><i class="fas fa-trash"></i></a>
                      </td>
                      <td v-else>
                        <a href="#" class="btn btn-success btn-sm text-light" @click.prevent="restaurar(empleado.id)"><i class="fas fa-trash-restore"></i></a>
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
          <!-- card datos personales -->      
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Datos personales</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">  
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="name">Nombres</label>
                  <input v-model="nombres" @change="validarCampo(nombres, 'name')" type="text" class="form-control datoEmpleado" id="name" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="lastname">Apellidos</label>
                  <input v-model="apellidos" @change="validarCampo(apellidos, 'lastname')" type="text" class="form-control datoEmpleado" id="lastname" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="sexo">Sexo</label>
                  <select v-model="sexo" @change="validarCampo(sexo, 'sexo')" id="sexo" name="sexo" class="form-control datoEmpleado" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="cedula">Cédula</label>	
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <select v-model="pre_cedula">
                          <option value="V-">V-</option>
                          <option value="E-">E-</option>
                        </select>
                      </div>
                    </div>
                    <input v-model="cedula" @keyup="validarCampo(cedula, 'cedula')" id="cedula" type="number" name="cedula" class="form-control datoEmpleado" min="4000000" required>
                  </div>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="nacimiento">Fecha de nacimiento</label>
                  <input v-model="fecha_nacimiento" @change="validarCampo(fecha_nacimiento, 'nacimiento')" type="date" min="1930-01-01" max="2000-01-01" class="form-control datoEmpleado" id="nacimiento" name="fecha_na" required>
                  <div class="invalid-feedback">
                          *Fecha invalida
                  </div>
                </div>
              </div> 
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="correo">Correo electrónico</label>
                  <input v-model="correo" @change="validarCampo(correo, 'correo')" type="email" class="form-control datoEmpleado" id="correo" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="telefono">Número de teléfono</label>	
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <select v-model="pre_telefono">
                          <option value="0414-">0414-</option>
                          <option value="0424-">0424-</option>
                          <option value="0416-">0416-</option>
                          <option value="0426-">0426-</option>
                          <option value="0412-">0412-</option>
                          <option value="0285-">0285-</option>
                        </select>
                      </div>
                    </div>
                    <input v-model="telefono" @keyup="validarCampo(telefono, 'telefono')" id="telefono" type="number" class="form-control datoEmpleado" required>
                  </div>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
              </div> 
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <!-- card registro de hijos -->
          <div class="card card-default collapsed-card">
              <div class="card-header">
                  <h3 class="card-title">Hijos</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
              </div> 
              <!-- /.card-header -->
              <div class="card-body" style="display: none;">  
                  <form>
                    <div class="form-row mt-2 border-bottom border-primary">
                      <div class="form-group col-md-4">
                        <label for="nombre_hijo">Nombre</label>
                        <input v-model="hijo.nombre" type="text" class="form-control" id="nombre_hijo" placeholder="Nombre">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="apellido_hijo">Apellido</label>
                        <input v-model="hijo.apellido" type="text" class="form-control" id="apellido_hijo" placeholder="Apellido">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="nacimiento_hijo">Fecha de nacimiento</label>
                        <input v-model="hijo.nacimiento" type="date" class="form-control" id="nacimiento_hijo">
                      </div>
                      <div class="col-md-4 form-group">
                        <label for="nivel_hijo">Nivel educativo</label>
                        <select v-model="hijo.nivel" id="nivel_hijo" name="nivel_hijo" class="form-control">
                          <option value="0" selected>Ninguno</option>
                          <option value="Inicial">Inicial</option>
                          <option value="Primaria">Primaria</option>
                          <option value="Bachillerato">Bachillerato</option>
                          <option value="Universidad">Universitario</option>
                        </select>
                      </div>
                      <div class="col-md-4 form-group">
                        <label for="discapacidad_hijo">Discapacidad</label>
                        <select v-model="hijo.discapacidad" id="discapacidad_hijo" name="discapacidad_hijo" class="form-control">
                          <option value="Ninguna" selected>Ninguna</option>
                          <option value="Visual">Visual</option>
                          <option value="Motora">Motora</option>
                          <option value="Intelectual">Intelectual</option>
                          <option value="Otra">Otra</option>
                        </select>
                      </div>
                      <div class="col-md-12 mb-4">
                        <button @click.prevent="agregarhijos()" class="btn btn-primary">Añadir</button>
                      </div>
                    </div>
                  </form>
                  <div class="border-bottom border-primary"></div>
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">Apellido</th>
                          <th scope="col">Edad</th>
                          <th scope="col">Educación</th>
                          <th scope="col">Discapacidad</th>
                          <th scope="col">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(dato, index) in arrayHijos" :key="index">
                          <th scope="row" v-text="index+1"></th>
                          <td v-text="dato.nombre"></td>
                          <td v-text="dato.apellido"></td>
                          <td v-text="edadHijo(dato.nacimiento)"></td>
                          <td v-text="(dato.nivel!='0')?dato.nivel:'Ninguno'"></td>
                          <td v-text="dato.discapacidad"></td>
                          <td>
                            <button @click="editarHijos(dato, index)" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                            <button @click="eliminarHijos(index)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash" ></i></button>
                          </td>
                        </tr>
                      </tbody>
                      <caption v-if="!arrayHijos.length">No tiene hijos registrados</caption>
                    </table>
                  </div>
              </div>
              <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <!-- card datos laborales -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Datos de Empleado</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">  
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="grado">Grado</label>
                  <select v-model="grado" @change="validarCampo(grado, 'grado')" id="grado" class="form-control datoEmpleado" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Profesional">Profesional</option>
                    <option value="Técnico">Técnico</option>
                    <option value="Apoyo">Apoyo</option>
                  </select>
                </div>
                <div class="col-md-2 mb-2 form-group">
                  <label for="nivel">Nivel</label>
                  <select v-model="nivel" @change="validarCampo(nivel, 'nivel')" id="nivel" class="form-control datoEmpleado" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="fecha_ingreso">Fecha de ingreso</label>
                  <input v-model="fecha_ingreso" @change="validarCampo(fecha_ingreso, 'fecha_ingreso'); calculaAñosServicio()" type="date" min="2000-01-01" max="2022-01-01" class="form-control datoEmpleado" id="fecha_ingreso" name="fecha_na" required>
                  <div class="invalid-feedback">
                          *Fecha invalida
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="departamento">Departamento</label>
                  <select v-model="departamento" @change="validarCampo(departamento, 'departamento')" id="departamento" class="form-control datoEmpleado" required>
                    <option disabled selected>Seleccionar</option>
                    <template v-for="(depa, index) in arrayDep">
                      <option :value="depa" :key="index">{{depa}}</option>
                    </template>
                  </select>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="grado">Grado de Instrucción</label>	
                  <select v-model="grado_instruccion" @change="validarCampo(grado_instruccion, 'grado_ins')" id="grado_ins" class="form-control datoEmpleado" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="T.S.U">T.S.U</option>
                    <option value="Profesional">Profesional</option>
                    <option value="Especialista">Especialista</option>
                    <option value="Maestria">Maestria</option>
                    <option value="Doctor">Doctor</option>
                  </select>                  
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="estadoEmpleado">Estado</label> 
                  <select v-model="estadoEmpleado" id="estadoEmpleado" class="form-control datoEmpleado" required>
                    <option value="Fijo">Fijo</option>
                    <option value="Contratado">Contratado</option>
                    <option value="Pensionado">Pensionado</option>
                    <option value="Jubilado">Jubilado</option>
                    <option value="Inactivo">Inactivo</option>
                  </select>
                </div>      
              </div>  
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!--  Registro de datos bancarios -->
          <banco
            :datosBancarios='datosBancarios'
            :id='id_persona'
            ref="banco"
          ></banco>
          <template v-if="accion=='registrar'">
            <button @click="registrar()" type="button" class="btn btn-primary btn-lg">Registrar</button>
          </template>
          <template v-if="accion=='editar'">
            <button @click="actualizarEmpleado()" type="button" class="btn btn-primary btn-lg">Actualizar</button>
          </template>
      </div><!-- /.container-fluid -->
      <template v-if="accion=='aggDatosSalarios'">
        <!-- Componente datos de salarios --> 
        <salarios 
            :grado="grado" 
            :nivel="nivel" 
            :id="id_empleado"
            :anos="añosServicio"
            :avisar="checked"
            :personal="tipoPersonal"
            :idSalario="id_salario"
            :instruccion="grado_instruccion"
            :hijos="arrayHijos"
            ref="salario"
        >
          
        </salarios>  
        <!-- /.componente -->
        <template v-if="id_empleado != ''">
          <button @click.prevent="actualizarEmpleado()" type="button" class="btn btn-primary btn-lg">Guardar</button>
        </template>
        <template v-else>
          <button @click.prevent="registrar()" type="button" class="btn btn-primary btn-lg">Registrar</button>
        </template>
      </template>
      <div class="container-fluid" v-if="accion=='ver'">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <strong><p v-text="nombres+' '+apellidos+'   '+pre_cedula+cedula"></p></strong>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr >
                      <td><strong>Datos personales</strong></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr >
                      <td v-text="'Sexo: '+sexo"></td>
                      <td v-text="'Correo electrónico: '+correo"></td>
                      <td v-text="'Número de teléfono: '+pre_telefono+telefono"></td>
                    </tr>
                    <tr >
                      <td colspan="3" v-text="'Fecha de nacimiento: '+fecha_nacimiento"> </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table">
                  <thead>
                    <tr >
                      <td><strong>Datos de empleado</strong></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr >
                      <td v-text="'Grado de instruccion: '+grado_instruccion"></td>
                      <td v-text="'Cargo: '+grado+' '+nivel"></td>
                      <td v-text="'Departamento: '+departamento"></td>
                    </tr>
                    <tr >
                      <td v-text="'Estado: '+estadoEmpleado"></td>
                      <td v-text="'Fecha de ingreso: '+fecha_ingreso"></td>
                      <td> </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table">
                  <thead>
                    <tr >
                      <td><strong>Cuenta bancaria</strong></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr >
                      <td v-text="`Banco: ${(banco.banco=='0102-')?'Banco de Venezuela':''}`"></td>
                      <td v-text="`Tipo de cuenta ${(banco.tipo_cuenta)?'Corriente':'Ahorro'}`"></td>
                    </tr>
                    <tr>
                      <td v-text="`Número de cuenta: ${banco.numero_cuenta}`"></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="col-12">
              <!-- card del listado de pagos-->
              <div class="card">
                <div class="card-header">
                  <h2><i>Historial de pagos</i></h2>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <a href="#" ><i aria-hidden="true" class='fa fa-search'></i></a>
                          </div>
                        </div>
                        <input id="searchPago" type="text" class="form-control" placeholder="Busqueda">
                      </div>
                    </div>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Código</th>
                      <th>Sueldo</th>
                      <th>Pago</th>
                      <th>Fecha</th>
                      <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr v-for="pago in arrayPagos" :key="pago.id">
                        <td v-text="pago.codigo"></td>
                        <td v-text="formatoDivisa(pago.sueldo)"></td>
                        <td v-text="formatoDivisa(pago.pago)"></td>
                        <td v-text="pago.fecha"></td>
                        <td>
                          <a href="#" @click.prevent="pagoPDF(pago.id, id_empleado)" style="color:#fff;" class="btn btn-danger btn-sm">PDF</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  
                    <ul class="pagination btn-group mr-2 mt-4" role="group" aria-label="First group">
                      <li v-if="pagination.current_page > 1">
                        <button type="button" class="btn btn-light" @click.prevent="cambioPaginaPagos(pagination.current_page - 1)">Atras</button>
                      </li>
                      <li v-for="page in pageNumber" :key="page" :class="[page == 1 ? 'active' : '']">
                        <button @click.prevent="cambioPaginaPagos(page)" :class="[page == isActive ? 'btn-primary' : 'btn-light']" v-text="page" type="button" class="btn"></button>
                      </li>
                      <li v-if="pagination.current_page < pagination.last_page">
                        <button type="button" class="btn btn-light" @click.prevent="cambioPaginaPagos(pagination.current_page + 1)">Siguiente</button>
                      </li>
                    </ul>
                  
                </div>
                <!-- /.card-body -->
              </div>
            <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

</template>
<script>
    export default {
        data() {
          return {
            nombres: "",
            apellidos: "",
            sexo: "Seleccionar",
            pre_cedula: "V-",
            cedula: "",
            correo: "",
            pre_telefono: "0414-",
            telefono: "",
            arrayHijos: [],
            hijo: {
              nombre:'',
              apellido:'',
              nacimiento:'',
              nivel:'0',
              discapacidad: 'Ninguna',
            },
            arrayDep: [
              "Secretaria",
              "Vice rectorado académico",
              "Vice rectorado Territorial",
              "Cultura",
              "Gestión del desarrollo Estratégico productivo",
              "Despacho",
              "Desarrollo de Política Estudiantil",
              "Planificación y presupuesto",
              "Planta Física",
              "Oficina de Gestión Administrativa",
              "Oficina de Gestión Humana",
              "Registro y Control Académico",
              "Oficina de Atención Ciudadana",
              "Grado y Certificación",
              "Vinculación con la Seguridad y Defensa de la Nación",
              "Programa Nacional De Formación Avanzada",
              "Universalizacion de la Educación",
              "Oficina de gestión Administrativa y Finanza de proyecto",
              "Gaceta Universitaria y Archivo Central",
              "Creación Intelectual y Desarrollo Socio Productivo",
              "Oficina de Tecnología de Información y la Comunicación",
              "Bienes Público",
              "Dpt. De Compra",
              "Dpt. Servicio General",
              "Dpt. De Contabilidad",
              "Dpt. De Caja",
              "Dpt. De Control de Gestión y Estadística",
              "Dpt. De Almacén."
            ],
            fecha_nacimiento: "",
            grado: "Seleccionar",
            nivel: "Seleccionar",
            fecha_ingreso: "",
            departamento: "Seleccionar",
            grado_instruccion: "Seleccionar",
            estadoEmpleado: "Contratado",
            tipoPersonal: "Administrativo",
            arrayempleado: [],
            pagination: {
                "total": 0,
                "current_page": 0,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
            },
            accion: 'listar',
            id_salario: 1,
            datosSalario: false,
            salarioTabla: 0,
            UT: '',
            arrayPagos: [],
            arrayDescuentos: [],
            beneficiosEmpleado: [],
            totalBene: 0,
            descuentosEmpleado: [],
            id_beneficiosAgregados:[],
            id_descuentosAgregados: [],
            añosServicio: 0,
            TotalprimaAntiguedad:0,
            banco: {},
            validBanco: false,
            error: [],
            id_empleado: "",
            id_persona: "",
            busqueda: "",
            criterio: ""
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
          },
        },
        methods: {
          listarEmpleado(page, busqueda, criterio){
            let me=this;
            var url= '/empleados?page='+page+'&busqueda='+busqueda+'&criterio='+criterio+'&tipo='+me.tipoPersonal;
            axios.get(url).then(function (response) {
                var respuesta= response.data.empleados.data;
                me.arrayempleado = respuesta;
                me.pagination = response.data.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
          },
          registrar(){
            let me = this;
            if(me.validarForm()){

              swal.fire({
                title: '¿Desea continuar con el registro?',
                text: "Una vez registrado, el empleado no podrá ser eliminado de la base de datos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                  let me = this;
                  let url = '/empleados/agregarNuevo';
                  axios.post(url, {
                    nombres:me.nombres,
                    apellidos:me.apellidos,
                    sexo:me.sexo,
                    cedula:me.pre_cedula+me.cedula,
                    correo:me.correo,
                    telefono:me.pre_telefono+me.telefono,
                    nacimiento:me.fecha_nacimiento,
                    grado: me.grado,
                    nivel: me.nivel,
                    fecha_ingreso: me.fecha_ingreso,
                    departamento: me.departamento,
                    grado_instruccion: me.grado_instruccion,
                    estado: me.estadoEmpleado,
                    id_salario: me.id_salario,
                    beneficios: me.id_beneficiosAgregados,
                    descuentos: me.id_descuentosAgregados,
                    tipo: me.tipoPersonal,
                    banco:me.banco,
                    hijos:me.arrayHijos
                  }).then(function(response){
                    if(response.data.respuesta){
                      Vue.toasted.error( 'Empleado existente, verifique los datos ingresados', 
                      {duration:2000, className:['alert', 'alert-danger']});
                      me.datosSalario = false;
                    }else{
                      swal.fire(
                        'Empleado agregado exitosamente',
                        '',
                        'success'
                      )
                      me.accion = "listar";
                      me.listarEmpleado(1, me.busqueda, me.criterio);
                      me.resetearInputs();
                    } 
                  }).catch(function(error){
                    console.log(error)
                  });
                }else return;
              })


            };
          },
          editarEmpleado(id){
            let me = this;

            let url = '/empleados/editarEmpleado/'+id;
            axios.get(url).then(function(response){
              let empleado = response.data.empleado[0];   
              let banco = response.data.banco;
              let hijos = response.data.hijos;

              me.nombres= empleado.nombres;
              me.apellidos= empleado.apellidos;
              me.sexo= empleado.sexo;
              me.pre_cedula= empleado.cedula.substring(0, 2);
              me.cedula= empleado.cedula.substring(2);
              me.correo= empleado.correo;
              me.pre_telefono= empleado.telefono.substring(0, 5);
              me.telefono= empleado.telefono.substring(5);
              me.fecha_nacimiento= empleado.nacimiento;
              me.grado= empleado.grado;
              me.nivel= empleado.nivel;
              me.fecha_ingreso= empleado.fechaIngreso;
              me.departamento= empleado.departamento;
              me.grado_instruccion= empleado.instruccion;
              me.estadoEmpleado= empleado.estado;
              me.id_empleado= empleado.id_empleado;
              me.id_persona= empleado.id_persona;
              me.arrayHijos= hijos;
              me.banco= {
                banco:banco.banco, 
                tipo_cuenta:banco.tipo_cuenta, 
                numero_cuenta:banco.numero_cuenta
              };
              me.calculaAñosServicio();

              if (me.accion=='ver') {
                me.arrayPagos = me.historialPagos(1, empleado.id_empleado);
              }
              
              //Hace referencia a la funcion editarBanco del componente hijo
              me.$refs.banco.editarBanco(empleado.id_persona);
            }).catch(function(error){
              console.log(error);
            });
            
          },
          historialPagos(page, id){
            let me = this;
            const url = '/empleados/historialPagos/'+id+'?page='+page;

            axios.get(url).then(function(response){
              me.arrayPagos = response.data.pagos.data;
              me.pagination = response.data.pagination;
            }).catch(function(error){
              console.log(error);
            });
          },
          pagoPDF(id, id_empleado){
            window.open('/pagos/pdf/'+id_empleado+'/'+id);
          },
          actualizarEmpleado(){
            let me = this;
            if(me.validarForm()){
                let me = this;
                let url = '/empleados/actualizarEmpleado';
                axios.put(url, {
                  nombres:me.nombres,
                  apellidos:me.apellidos,
                  sexo:me.sexo,
                  cedula:me.pre_cedula+me.cedula,
                  correo:me.correo,
                  telefono:me.pre_telefono+me.telefono,
                  nacimiento:me.fecha_nacimiento,
                  grado: me.grado,
                  nivel: me.nivel,
                  fecha_ingreso: me.fecha_ingreso,
                  departamento: me.departamento,
                  grado_instruccion: me.grado_instruccion,
                  estado: me.estadoEmpleado,
                  beneficios: me.id_beneficiosAgregados,
                  descuentos: me.id_descuentosAgregados,
                  id_persona: me.id_persona,
                  id_empleado: me.id_empleado,
                  banco:me.banco,
                  hijos:me.arrayHijos
                }).then(function(response){
                  
                  swal.fire(
                      'Actualizado exitosamente',
                      '',
                      'success');

                    me.accion = "listar";
                    me.listarEmpleado(1, me.busqueda, me.criterio);
                    me.resetearInputs();
                   
                }).catch(function(error){
                  console.log(error)
                });
            };
          },
          validarForm(){
            const inputs = document.getElementsByClassName('datoEmpleado');
            //Validar todos los campos vacios
              for (let i = 0; i < inputs.length; i++) {               
                const element = inputs[i];
                if(!element.value || element.value == "Seleccionar"){                  
                  if (this.error.indexOf(element.id)) {
                      element.classList.add('is-invalid');
                      this.error.push(element.id);
                  };
                }else{
                  element.classList.remove('is-invalid');
                  element.classList.add('is-valid');
                  let indiceElement = this.error.indexOf(element.id);
                    //Verifica si existe el indice
                    if (indiceElement!== -1) {
                      this.error.splice(indiceElement, 1);
                    };
                };
                this.validarCampo(element.value, element.id);                
              };

            if(!this.validBanco){
              Vue.toasted.error( 'Verifica los datos bancarios', {duration:2000});
              return false;
            }

           //console.log(this.error);
            if(!this.error.length){
              this.plegarCard();
              if (this.datosSalario) {
                return true;
              };
              
            }else{
              Vue.toasted.error( 'Verifica los datos del empleado', {duration:2000});
            }

          },
          checked(status, data){
            if (status && !data) {
              Vue.toasted.error( 'Agregue los datos del salario', {duration:2000});
            }else if (status && data) {
              this.datosSalario = status;
              this.id_beneficiosAgregados = data[0];
              this.id_descuentosAgregados = data[1];
            }else{
              this.datosSalario = false;
            }
          },
          datosBancarios(status, data){
            const num = data.numero_cuenta;
            if(!status && (num.length != 20 || num.length != 23)){
              this.validBanco = false;
            }else{
              this.validBanco = true;
              data.numero_cuenta = num.replaceAll('-', '');
              this.banco = data;
            }
            
          },
          validarCampo(campo, id){
            //Validar campos al escribir o cambiar
            if(id && campo != "" && campo != "Seleccionar"){
              const input = document.getElementById(id);
              input.classList.remove('is-invalid');
              input.classList.add('is-valid');
              switch(id){
                case "cedula":
                    if(campo.length < 7 || campo.length > 8){
                      input.classList.add('is-invalid');
                      if (this.error.indexOf(input.id)) {                       
                        this.error.push(input.id);
                      };
                      
                    }else{
                      input.classList.remove('is-invalid')
                      input.classList.add('is-valid')
                      let indiceElement = this.error.indexOf(input.id);
                      //Verifica si existe el indice
                      if (indiceElement!== -1) {
                        this.error.splice(indiceElement, 1);
                      };
                    }
                  break;
                  case "telefono":
                    if(campo.length != 7){
                      input.classList.add('is-invalid');
                      if (this.error.indexOf(input.id)) {                       
                        this.error.push(input.id);
                      };
                    }else{
                      input.classList.remove('is-invalid');
                      input.classList.add('is-valid');
                      let indiceElement = this.error.indexOf(input.id);
                      //Verifica si existe el indice
                      if (indiceElement!== -1) {
                        this.error.splice(indiceElement, 1);
                      };
                    }
                  break;
                  case "nacimiento":
                    if(input.value < input.min || input.value > input.max){
                    input.classList.add('is-invalid');
                      if (this.error.indexOf(input.id)) {                       
                        this.error.push(input.id);
                      };
                    }
                    else{
                      input.classList.remove('is-invalid');
                      input.classList.add('is-valid');
                      let indiceElement = this.error.indexOf(input.id);
                      //Verifica si existe el indice
                      if (indiceElement!== -1) {
                        this.error.splice(indiceElement, 1);
                      };
                    }
                  break;
                  case "fecha_ingreso":
                    if(input.value < input.min || input.value > input.max){
                      input.classList.add('is-invalid');
                      if (this.error.indexOf(input.id)) {
                        this.error.push(input.id);
                      };
                    }
                    else{
                      input.classList.remove('is-invalid');
                      input.classList.add('is-valid');
                      let indiceElement = this.error.indexOf(input.id);
                      //Verifica si existe el indice
                      if (indiceElement!== -1) {
                        this.error.splice(indiceElement, 1);
                      };
                    }
                  break;
              }; 
            };
           //console.log(this.error);
          },
          calculaAñosServicio(){
            //Calcula los años de antiguedad a partir de la fecha de ingreso
            let ingreso = this.fecha_ingreso;
            let me = this;
            ingreso = new Date(ingreso);
            let actual = new Date();

            let año = ingreso.getFullYear();
            let mes = ingreso.getMonth(); 

            let antiguedad = actual.getFullYear()-año;
            if (mes >= actual.getMonth()  && antiguedad != 0) {
              antiguedad--;
            };
            me.añosServicio=antiguedad;
          },
          plegarCard(){
            this.accion="aggDatosSalarios";
            if (!this.datosSalario) {
              Vue.toasted.info( 'Confirmar datos de salario del empleado', {duration:2000});
            }
          },
          resetearInputs(){
            let me = this;

            me.nombres="";
            me.apellidos="";
            me.sexo="";
            me.pre_cedula="";
            me.cedula="";
            me.correo="";
            me.pre_telefono="";
            me.telefono="";
            me.fecha_nacimiento="";
            me.grado="Seleccionar";
            me.nivel="Seleccionar";
            me.fecha_ingreso="";
            me.departamento="Seleccionar";
            me.grado_instruccion="Seleccionar";
            me.sexo= "Seleccionar"
            me.pre_cedula= "V-"
            me.pre_telefono= "0414-"
            me.salarioTabla = 0;
            me.añosServicio = 0;
            me.TotalprimaAntiguedad= 0;
            me.totalBene = 0;
            me.id_beneficiosAgregados= [];
            me.id_descuentosAgregados= [];
            me.id_persona = "";
            me.id_empleado = "";
            me.beneficiosEmpleado= [];
            me.descuentosEmpleado= [];
            me.datosSalario=false;
            me.banco = {};
          },
          formatoDivisa(number){
           let monto = new Intl.NumberFormat('en-US').format(number);
           return monto;
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarEmpleado(page, this.busqueda, this.criterio);
          },
          cambioPaginaPagos(page){
            this.pagination.current_page = page;
            this.historialPagos(page, this.id_empleado);
          },
          agregarhijos(){
            if(this.hijo.nombre && this.hijo.apellido && this.hijo.nacimiento){
              this.arrayHijos.push(this.hijo);
              this.hijo = {
                nombre:'',
                apellido:'',
                nacimiento:'',
                nivel:'0',
                discapacidad: 'Ninguna',
              };
            }else{
              Vue.toasted.error( 'Campos requeridos', {duration:2000});
            }
            
          },
          edadHijo(fecha){
            //Calcula la edad del hijo del trabajador

            fecha = new Date(fecha);
            let actual = new Date();

            let año = fecha.getFullYear();
            let mes = fecha.getMonth(); 

            let edad = actual.getFullYear()-año;

            if (mes >= actual.getMonth()  && edad != 0) {
              edad--;
            };

            return edad;
          },
          editarHijos(dato, index){
            this.hijo = dato;
            this.eliminarHijos(index);
          },
          eliminarHijos(index){
            this.arrayHijos.splice(index, 1);
          },
          eliminarEmpleado(id, nombre){
            let me = this;
            let url = '/empleados/delete/'+id;
            swal.fire({
              title: `¿Seguro que desea eliminar a ${nombre}?`,
              text: "Los datos del trabajador se mantendran en la papelera",
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
                        'Empleado eliminado',
                        '',
                        'success');
                  me.accion = "listar";
                  me.listarEmpleado(1, me.busqueda, me.criterio);
                  me.resetearInputs();
                }).catch(function(error){
                  console.error(error);
                });
              }else return;
            });   
          },
          restaurar(id){
            let url = 'empleados/restore/'+id;
            let me = this;
            axios.get(url).then((response)=>{
              swal.fire(
                        'Empleado restaurado',
                        '',
                        'success');
                  me.accion = "listar";
                  me.listarEmpleado(1, me.busqueda, me.criterio);
                  me.resetearInputs();
            }).catch((error)=>{
              console.error(error);
            })
          }
        }, 
        mounted() {
          this.listarEmpleado(1, this.busqueda, this.criterio);
        }
    };
</script>
<style>
  .collapsed-card.card-body{
    display:none;
  }
  .collapsed-card.card-footer{
    display:none;
  }

</style>