<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 sm-4">      
            <template v-if="accion=='listar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trabajadores</a></li>
                <li class="breadcrumb-item active">Docente</li>
              </ol>
            </template>
            <template v-if="accion=='registrar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"><a href="#" @click="accion='listar'; resetearInputs()">Docente</a></li>
                <li class="breadcrumb-item">Registrar</li>
              </ol>
            </template>
            <template v-if="accion=='aggDatosSalarios'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'; resetearInputs()">Docente</a></li>
                <li v-if="id_empleado==''" class="breadcrumb-item" ><a href="#" @click.prevent="accion='registrar';">Registrar</a></li>
                <li v-else class="breadcrumb-item" ><a href="#" @click.prevent="accion='editar';">Editar</a></li>
                <li class="breadcrumb-item">Agregar datos salariales</li>
              </ol>
            </template>
            <template v-if="accion=='ver'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"><a href="#" @click="accion='listar'; resetearInputs()">Docente</a></li>
                <li class="breadcrumb-item">Consultar</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"><a href="#" @click="accion='listar'; resetearInputs()">Docente</a></li>
                <li class="breadcrumb-item">Editar</li>
              </ol>
            </template>
            <template v-if="accion=='AggAdmin'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item active"  @click="accion='listar'; resetearInputs()"><a href="#">Docente</a></li>
                <li class="breadcrumb-item active" @click="accion='registrar'"><a href="#">Registrar</a></li>
                <li class="breadcrumb-item">Agregar Docente Administrativo</li>
              </ol>
            </template>
            <template v-if="accion=='editarDocenteAdmin'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Trabajadores</li>
                <li class="breadcrumb-item active"  @click="accion='listar'; resetearInputs()"><a href="#">Docente</a></li>
                <li class="breadcrumb-item">Editar Docente Administrativo</li>
              </ol>
            </template>
          </div>
          <div v-if="accion=='registrar'" class="col-md-6 sm-8" align="right">
            <button type="button" @click="accion='AggAdmin'" class="btn btn-primary">Agregar Administrativo</button>
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
                <button @click="accion='registrar'; resetearInputs()" type="button" class="btn btn-light">
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
                    <th>PNF</th>
                    <th>Categoria</th>
                    <th>Dedicación</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="docente in arraydocente" :key="docente.id">
                      <td v-text="docente.nombres"></td>
                      <td v-text="docente.apellidos"></td>
                      <td v-text="docente.pnf"></td>
                      <td v-text="docente.categoria"></td>
                      <td v-text="docente.dedicacion"></td>
                      <td v-if="criterio != 'trashed'">
                        <template v-if="docente.tipoPersonal=='Docente'">
                          <a href="#" style="color:#fff;" class="btn btn-info btn-sm" @click.prevent="accion='ver'; mostrarEmpleado(docente.id)"><i class="far fa-eye" ></i></a>
                          <a href="#" style="color:#fff;" class="btn btn-success btn-sm" @click.prevent="accion='editar'; mostrarEmpleado(docente.id)"><i class="fas fa-edit"></i></a>
                          <a href="#" class="btn btn-danger btn-sm text-light" @click.prevent="eliminarEmpleado(docente.id, `${docente.nombres} ${docente.apellidos}`)"><i class="fas fa-trash"></i></a>
                        </template>
                        <template v-else>
                          <a href="#" @click.prevent="accion='editarDocenteAdmin'; mostrarEmpleado(docente.id)"><i class="fas fa-edit"></i></a>
                          <a href="#" @click.prevent="retirarAdmin(docente.id_empleado)"><i class="fa fa-trash"></i></a>
                        </template>
                      </td>
                      <td v-else>
                        <a href="#" class="btn btn-success btn-sm text-light" @click.prevent="restaurar(docente.empleado_id)"><i class="fas fa-trash-restore"></i></a>
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
                  <input v-model="nombres" @change="validarCampo(nombres, 'name')" type="text" class="form-control dato_docente" id="name" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="lastname">Apellidos</label>
                  <input v-model="apellidos" @change="validarCampo(apellidos, 'lastname')" type="text" class="form-control dato_docente" id="lastname" required>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="sexo">Sexo</label>
                  <select v-model="sexo" @change="validarCampo(sexo, 'sexo')" id="sexo" name="sexo" class="form-control dato_docente" required>
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
                    <input v-model="cedula" @keyup="validarCampo(cedula, 'cedula')" id="cedula" type="number" name="cedula" class="form-control dato_docente" min="4000000" required>
                  </div>
                  <div class="invalid-feedback">
                          *Este campo es requerido
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="nacimiento">Fecha de nacimiento</label>
                  <input v-model="fecha_nacimiento" @change="validarCampo(fecha_nacimiento, 'nacimiento')" type="date" min="1930-01-01" max="2000-01-01" class="form-control dato_docente" id="nacimiento" name="fecha_na" required>
                  <div class="invalid-feedback">
                          *Fecha invalida
                  </div>
                </div>
              </div> 
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="correo">Correo electrónico</label>
                  <input v-model="correo" @change="validarCampo(correo, 'correo')" type="email" class="form-control dato_docente" id="correo" required>
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
                    <input v-model="telefono" @keyup="validarCampo(telefono, 'telefono')" id="telefono" type="number" class="form-control dato_docente" required>
                  </div>
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
                  <label for="grado">Categoria</label>
                  <select v-model="categoria" @change="validarCampo(categoria, 'categoria')" id="categoria" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Instructor">Instructor</option>
                    <option value="Asistente">Asistente</option>
                    <option value="Agregado">Agregado</option>
                    <option value="Asociado">Asociado</option>
                    <option value="Titular">Titular</option>
                    <option value="Auxiliar Docente">Auxiliar</option>
                  </select>
                </div>
                <template v-if="categoria=='Auxiliar Docente'">
                  <div class="col-md-2 mb-2 form-group">
                    <label for="grado_auxiliar">Grado</label>
                    <select v-model="grado_auxiliar" @change="validarCampo(grado_auxiliar, 'grado_auxiliar')" id="grado_auxiliar" class="form-control dato_docente" required>
                      <option selected value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                    </select>
                  </div>
                </template>
                <div class="col-md-4 mb-2 form-group">
                  <label for="dedicacion">Dedicación</label>
                  <select v-model="dedicacion" @change="validarCampo(dedicacion, 'dedicacion')" id="dedicacion" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Exclusiva">Exclusiva</option>
                    <option value="Tiempo Completo">Tiempo Completo</option>
                    <option value="Medio Tiempo">Medio Tiempo</option>
                    <option value="Convencional">Convencional</option>
                  </select>
                </div>
                <template v-if="dedicacion=='Convencional'">
                  <div class="col-md-2 mb-2 form-group">
                    <label for="horas">Horas</label>
                    <select v-model="horas_convencional" @change="validarCampo(horas_convencional, 'horas')" id="horas" class="form-control dato_docente" required>
                      <option selected value="7">7</option>
                      <option value="6">6</option>
                      <option value="5">5</option>
                      <option value="4">4</option>
                      <option value="3">3</option>
                      <option value="2">2</option>
                    </select>
                  </div>
                </template>
                <div class="col-md-4 mb-2 form-group">
                  <label for="fecha_ingreso">Fecha de ingreso</label>
                  <input v-model="fecha_ingreso" @change="validarCampo(fecha_ingreso, 'fecha_ingreso'); calculaAñosServicio()" type="date" min="2004-01-01" max="2021-01-01" class="form-control dato_docente" id="fecha_ingreso" name="fecha_na" required>
                  <div class="invalid-feedback">
                          *Fecha invalida
                  </div>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="pnf">PNF</label>
                  <select v-model="docente_pnf" @change="validarCampo(docente_pnf, 'pnf')" id="pnf" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Mecánica">Mecánica</option>
                    <option value="Informática">Informática</option>
                    <option value="Electricidad">Electricidad</option>
                    <option value="Geología">Geología</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="pnf">Instrucción</label>
                  <select v-model="grado_instruccion" @change="validarCampo(grado_instruccion, 'instruccion');" id="instruccion" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="T.S.U">T.S.U</option>
                    <option value="Profesional">Profesional</option>
                    <option value="Especialista">Especialista</option>
                    <option value="Maestria">Maestria</option>
                    <option value="Doctor">Doctor</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2 form-group">
                  <label for="estadoEmpleado">Estado</label> 
                  <select v-model="estadoDocente" id="estadoDocente" class="form-control dato_docente" required>
                    <option value="Fijo">Fijo</option>
                    <option value="Contratado">Contratado</option>
                    <option value="Pensionado">Pensionado</option>
                    <option value="Jubilado">Jubilado</option>
                  </select>
                </div>      
              </div>  
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            </div>
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
            :gradoAuxiliar="grado_auxiliar" 
            :categoria="categoria"
            :dedicacion="dedicacion"
            :tiempoCon="horas_convencional"
            :id="id_empleado"
            :anos="añosServicio"
            :avisar="checked"
            :personal="tipoPersonal"
            :idSalario="id_salario"
            :instruccion="grado_instruccion"
            :hijos="arrayHijos"
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
      <div class="container-fluid" v-if="accion=='AggAdmin' || accion=='editarDocenteAdmin'">
        <!-- Formulario de registro de -->
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
              <template v-if="accion=='AggAdmin'">
                <div class="col-md-6 form-group">
                  <v-select
                   @search="buscarAdmin"
                   :options="arrayAdmin"
                   label="admin"
                   :reduce="admin => admin.id"
                   :placeholder="'Buscar personal...'"
                   v-model="id_empleado"
                   ></v-select>
                </div>
              </template>
              <template v-else>
                <div class="col-md-6">
                  <p v-text="`${nombres} ${apellidos} ${pre_cedula}${cedula} (Personal Administrativo)`"></p>
                </div>
              </template>
            </div>  
              <div class="row">
                <div class="col-md-4 mb-2 form-group">
                  <label for="grado">Categoria</label>
                  <select v-model="categoria" @change="validarCampo(categoria, 'categoria')" id="categoria" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Instructor">Instructor</option>
                    <option value="Asistente">Asistente</option>
                    <option value="Agregado">Agregado</option>
                    <option value="Asociado">Asociado</option>
                    <option value="Titular">Titular</option>
                    <option value="Auxiliar Docente">Auxiliar</option>
                  </select>
                </div>
                <template v-if="categoria=='Auxiliar Docente'">
                  <div class="col-md-2 mb-2 form-group">
                    <label for="grado_auxiliar">Grado</label>
                    <select v-model="grado_auxiliar" @change="validarCampo(grado_auxiliar, 'grado_auxiliar')" id="grado_auxiliar" class="form-control dato_docente" required>
                      <option selected value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                    </select>
                  </div>
                </template>
                <div class="col-md-4 mb-2 form-group">
                  <label for="dedicacion">Dedicación</label>
                  <select v-model="dedicacion" @change="validarCampo(dedicacion, 'dedicacion')" id="dedicacion" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Convencional">Convencional</option>
                  </select>
                </div>
                <template v-if="dedicacion=='Convencional'">
                  <div class="col-md-2 mb-2 form-group">
                    <label for="horas">Horas</label>
                    <select v-model="horas_convencional" @change="validarCampo(horas_convencional, 'horas')" id="horas" class="form-control dato_docente" required>
                      <option selected value="7">7</option>
                      <option value="6">6</option>
                      <option value="5">5</option>
                      <option value="4">4</option>
                      <option value="3">3</option>
                      <option value="2">2</option>
                    </select>
                  </div>
                </template>
                <div class="col-md-4 mb-2 form-group">
                  <label for="pnf">PNF</label>
                  <select v-model="docente_pnf" @change="validarCampo(docente_pnf, 'pnf')" id="pnf" class="form-control dato_docente" required>
                    <option disabled selected>Seleccionar</option>
                    <option value="Mecánica">Mecánica</option>
                    <option value="Informática">Informática</option>
                    <option value="Electricidad">Electricidad</option>
                    <option value="Geología">Geociencias</option>
                    <option value="Calidad y Ambiente">Calidad y Ambiente</option>
                    <option value="Ingenieria de Mantenimiento">Ingenieria de Mantenimiento</option>
                    <option value="Orfebrería">Orfebrería</option>
                    <option value="Ingenieria de los Materiales">Ingenieria de los Materiales</option>
                    <option value="Higiene y Seguridad Laboral">Higiene y Seguridad Laboral</option>
                  </select>
                </div>      
              </div>  
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            </div>
          </div>
          <!-- /.card -->
          <template v-if="accion=='AggAdmin'">
            <button @click="regDocenteAd()" type="button" class="btn btn-primary btn-lg">Registrar</button>
          </template>
          <template v-else>
            <button @click="actualizarDocenteAdmin()" type="button" class="btn btn-primary btn-lg">Actualizar</button>
          </template>            
      </div><!-- /.container-fluid -->
      <div class="container-fluid" v-if="accion=='ver'">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <strong><p v-text="nombres+' '+apellidos+'   '+pre_cedula+cedula"></p></strong>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table1" class="table">
                  <thead>
                    <tr>
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
                      <td v-text="'Fecha de nacimiento: '+fecha_nacimiento"></td>
                      <td> </td>
                      <td> </td>
                    </tr>
                  </tbody>
                </table>
                <table id="table2" class="table">
                  <thead>
                    <tr >
                      <td><strong>Datos de empleado</strong></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr >
                      <td v-text="'Grado de instruccion: '+grado_instruccion"></td>
                      <td v-text="(categoria=='Auxiliar Docente')?'Categoria: '+categoria+' '+grado_auxiliar:'Categoria: '+categoria"></td>       
                      <td v-text="(dedicacion=='Convencional')?'Dedicación: '+dedicacion+' '+horas_convencional+' Horas':'Dedicación: '+dedicacion"></td> 
                      <td v-text="'PNF: '+docente_pnf"></td>
                    </tr>
                    <tr >
                      <td v-text="'Estado: '+estadoDocente"></td>
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
            <!-- /.card -->
          </div>
        </div>
      </div>
      <!-- Button trigger modal -->
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
            fecha_nacimiento: "",
            arrayHijos: [],
            hijo: {
              nombre:'',
              apellido:'',
              nacimiento:'',
              nivel:'0',
              discapacidad: 'Ninguna',
            },
            categoria: "Seleccionar",
            grado_auxiliar: "1",
            dedicacion: "Seleccionar",
            horas_convencional: "7",
            fecha_ingreso: "",
            docente_pnf: "Seleccionar",
            grado_instruccion: "Seleccionar",
            estadoDocente: "Contratado",
            tipoPersonal: "Docente",
            arraydocente: [],
            arrayAdmin: [],
            pagination: {
                "total": 0,
                "current_page": 0,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
              },
            accion: 'listar',
            id_salario: 3,
            id_beneficiosAgregados:[],
            id_descuentosAgregados: [],
            datosSalario: false,
            salarioTabla: 0,
            UT: '',
            arrayPagos: [],
            añosServicio: 0,
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
          }
        },
        methods: {
          listarEmpleado(page, busqueda, criterio){
            let me=this;
            var url= '/docentes?page='+page+'&busqueda='+busqueda+'&criterio='+criterio+'&tipo='+me.tipoPersonal;
            axios.get(url).then(function (response) {
                var respuesta= response.data.empleados.data;
                me.arraydocente = respuesta;
                me.pagination = response.data.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
          },
          buscarAdmin(search, loading){
            let me=this;
            loading(true)
              var url= '/docentes/aggAdmin?filtro='+search;
              //Consultar todo el personal administrativo
              axios.get(url).then(function (response) {
                  var respuesta= response.data.Padmin;
                  me.arrayAdmin=[];
                  for(var i = 0; i < respuesta.length; i++){
                    me.arrayAdmin.push({admin:respuesta[i].nombres +" "+ respuesta[i].apellidos +" "+ respuesta[i].cedula, id:respuesta[i].empleado_id});
                  };
                loading(false);
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
                  let url = '/docentes/agregarNuevo';
                  axios.post(url, {
                    nombres:me.nombres,
                    apellidos:me.apellidos,
                    sexo:me.sexo,
                    cedula:me.pre_cedula+me.cedula,
                    correo:me.correo,
                    telefono:me.pre_telefono+me.telefono,
                    nacimiento:me.fecha_nacimiento,
                    categoria: (me.categoria=='Auxiliar Docente')?`${me.categoria} ${me.grado_auxiliar}`:me.categoria,
                    dedicacion: (me.dedicacion=='Convencional')?`${me.dedicacion} ${me.horas_convencional} Horas`:me.dedicacion,
                    fecha_ingreso: me.fecha_ingreso,
                    docente_pnf: me.docente_pnf,
                    instruccion:me.grado_instruccion,
                    estado: me.estadoDocente,
                    id_salario: me.id_salario,
                    beneficios: me.id_beneficiosAgregados,
                    descuentos: me.id_descuentosAgregados,
                    tipo: me.tipoPersonal,
                    banco: me.banco,
                    hijos:me.arrayHijos,
                  }).then(function(response){
                    if(response.data.respuesta){
                      Vue.toasted.error( 'Empleado existente, verifique los datos ingresados', {duration:2000, className:['alert', 'alert-danger']});
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
          //Funcion para registrar a Administrativo como Docente
          regDocenteAd(){
            let me = this;
            
            if (me.dedicacion != "Seleccionar" && me.categoria != "Seleccionar" && me.docente_pnf != "Seleccionar" && me.horas_convencional != "Seleccionar" && me.id_empleado != "Seleccionar") {
                let url = '/docentes/registrarAdmin';
              axios.post(url, {
                categoria: (me.categoria=='Auxiliar Docente')?`${me.categoria} ${me.grado_auxiliar}`:me.categoria,
                dedicacion: (me.dedicacion=='Convencional')?`Tiempo ${me.dedicacion} ${me.horas_convencional} Horas`:me.dedicacion,
                pnf: me.docente_pnf,
                id_empleado: me.id_empleado
              }).then(function(response){

                if(!response.data.respuesta){
                  Vue.toasted.error( 'Docente existente, verifique los datos ingresados', {duration:2000, className:['alert', 'alert-danger']});
                }else{
                  swal.fire(
                    'Empleado agregado exitosamente',
                    '',
                    'success'
                  )
                  me.accion = "listar";
                  me.listarEmpleado(1, me.busqueda, me.criterio);
                  me.resetearInputs();
                };
                
              }).catch(function(error){
                console.log(error);
              });
            };
          },
          mostrarEmpleado(id){
            let me = this;
            let url = '/docentes/mostrarDocente/'+id;
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
              //Extraer el grado de Auxiliar Docente
              if (empleado.categoria.includes('Auxiliar')) {
                me.categoria= empleado.categoria.replace(/\s[1-3]/, "");
                me.grado_auxiliar= empleado.categoria.match(/[1-3]/)[0];
              }else{
                me.categoria= empleado.categoria;
              };
              //Extraer las horas convencionales 
              if (empleado.dedicacion.includes('Convencional')) {
                me.dedicacion= 'Convencional';
                me.horas_convencional= empleado.dedicacion.match(/[1-7]/)[0];
              }else{
                me.dedicacion=empleado.dedicacion
              };
              me.docente_pnf= empleado.pnf;
              me.fecha_ingreso= empleado.fechaIngreso;
              me.departamento= empleado.departamento;
              me.grado_instruccion= empleado.instruccion;
              me.estadoDocente= empleado.estado;
              me.id_empleado= empleado.id_empleado;
              me.id_persona= empleado.id_persona;
              me.arrayHijos= hijos;
              me.calculaAñosServicio();
              
              me.banco = banco;

              if (me.accion=='ver') {
                me.arrayPagos = me.historialPagos(1, empleado.id_empleado);
              };

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
                let url = '/docentes/actualizarDocente';
                axios.put(url, {
                  nombres:me.nombres,
                  apellidos:me.apellidos,
                  sexo:me.sexo,
                  cedula:me.pre_cedula+me.cedula,
                  correo:me.correo,
                  telefono:me.pre_telefono+me.telefono,
                  nacimiento:me.fecha_nacimiento,
                  categoria: (me.categoria=='Auxiliar Docente')?`${me.categoria} ${me.grado_auxiliar}`:me.categoria,
                  dedicacion: (me.dedicacion=='Convencional')?`Tiempo ${me.dedicacion} ${me.horas_convencional} Horas`:me.dedicacion,
                  fecha_ingreso: me.fecha_ingreso,
                  docente_pnf: me.docente_pnf,
                  grado_instruccion: me.grado_instruccion,
                  estado: me.estadoDocente,
                  beneficios: me.id_beneficiosAgregados,
                  descuentos: me.id_descuentosAgregados,
                  id_persona: me.id_persona,
                  id_empleado: me.id_empleado,
                  banco:me.banco,
                  hijos:me.arrayHijos,
                }).then(function(response){
                  
                  swal.fire(
                      'Actualizado exitosamente',
                      '',
                      'success');

                    
                    me.listarEmpleado(1, me.busqueda, me.criterio);
                    me.accion = "listar";
                    me.resetearInputs();
                   
                }).catch(function(error){
                  console.log(error)
                });
            };
          },
          actualizarDocenteAdmin(){
            let me = this;
            let url = 'docentes/actualizarDocenteAdmin'
            axios.put(url, {
                  categoria: (me.categoria=='Auxiliar Docente')?`${me.categoria} ${me.grado_auxiliar}`:me.categoria,
                  dedicacion: (me.dedicacion=='Convencional')?`Tiempo ${me.dedicacion} ${me.horas_convencional} Horas`:me.dedicacion,
                  docente_pnf: me.docente_pnf,
                  id_empleado: me.id_empleado 
                }).then(function(response){
                  swal.fire(
                      'Actualizado exitosamente',
                      '',
                      'success');
                    me.listarEmpleado(1, me.busqueda, me.criterio);
                    me.accion = "listar";
                    me.resetearInputs();
                   
                }).catch(function(error){
                  console.log(error)
                });
          },
          retirarAdmin(id){
            let me = this;
            let url = 'docentes/retirarDocenteAdmin/'+id;

            swal.fire({
                title: "Se retirará del personal docente",
                text: "¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                  axios.get(url).then(function(response){
                    swal.fire(
                          'Retirado exitosamente',
                          '',
                          'success'
                        );
                    me.accion = "listar";
                    me.listarEmpleado(1, me.busqueda, me.criterio);
                    me.resetearInputs();
                  }).catch(function(error){
                    console.log(error);
                  });
                };
              });    
          },
          validarForm(){
            const inputs = document.getElementsByClassName('dato_docente');
            //Validar todos los campos vacios
              for (let i = 0; i < inputs.length; i++) {               
                const element = inputs[i];
                if(!element.value || element.value == "Seleccionar"){                  
                  if (this.error.indexOf(element.id)) {
                      element.classList.add('is-invalid');
                      this.error.push(element.id);
                    };
                  this.error.push(element.id);
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
            };
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
            if(id && campo != "" && campo!="Seleccionar"){
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
            me.categoria="Seleccionar";
            me.grado_auxiliar="1"
            me.dedicacion="Seleccionar";
            me.horas_convencional="7"
            me.docente_pnf="Seleccionar";
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
            me.beneficiosEmpleado= [];
            me.descuentosEmpleado= [];
            me.datosSalario = false;
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
          formatoDivisa(number){
           let monto = new Intl.NumberFormat('en-US').format(number);
           return monto;
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarEmpleado(page, this.busqueda, this.criterio);
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