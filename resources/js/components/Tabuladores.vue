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
                <li class="breadcrumb-item active">Tabuladores</li>
              </ol>
            </template>
            <template v-if="accion=='editar'">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Salarios</li>
                <li class="breadcrumb-item active"><a href="#" @click.prevent="accion='listar'">Tabuladores</a></li>
                <li class="breadcrumb-item">Editar</li>
              </ol>
            </template>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- card tabulador Administrativo -->      
        <div class="card card-default" id="tabAdmin">
          <div class="card-header" >
            <h3 class="card-title" @mouseover="hoverEdit('admin', true)" @mouseleave="hoverEdit('admin', false)">
              Administrativo  
              <a id="admin" @click.prevent="accion='editarTabAdmin';" style="display:none;" href="#"><span class="badge">Editar</span><i class="fas fa-pen" aria-hidden="true"></i></a>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div> 
          <!-- /.card-header -->
          <div class="card-body">  
            <div class="row">
              <table id="tab1" class="table table-bordered">
                  <thead>
                  <tr>
                    <th>Cargo</th>
                    <th>Salario base</th>
                  </tr>
                  </thead>
                  <tbody>
                    <template v-for="(tab, key) in pagAdmin">
                      <tr v-for="(sal, ind) in tab">
                        <td>{{key+' Nivel '+parseInt(ind+1)}}</td>
                        <template v-if="accion=='editarTabAdmin'">
                          <td width="40%">
                            <div class="form-group row" style="margin:0;">
                              <div class="col-sm-4">
                                <input :id="'input'+key+ind" @change="validarCampo(tabAdmin[key][ind], 'input'+key+ind)" v-model="tabAdmin[key][ind]" type="text" class="form-control form-control-sm">
                              </div>
                            </div>
                          </td>
                        </template>
                        <template v-else>
                          <td width="40%">{{formatoDivisa(sal)}}</td>
                        </template>
                      </tr>
                    </template>
                  </tbody>
              </table>
              <template v-if="accion=='editarTabAdmin'">
                <div class="col col-md-3 mt-2">
                  <button @click="actualizarTab(tabAdmin_id, tabAdmin)" type="button" class="btn btn-primary">Guardar cambios</button>
                </div>
                <div class="col col-md-2 mt-2">
                  <button @click.prevent="accion='listar'; tabuladores()" type="button" class="btn btn-light">Descartar</button>
                </div>
              </template>
              <template v-else>
                <div class="col col-md-6 btn-group" role="group">
                <button type="button" @click="paginaAdmin('profesional')" :class="(pagAdmin.Profesional)?'btn-primary':'btn-light'" class="btn">Profesional</button>
                <button type="button" @click="paginaAdmin('tecnico')" :class="(pagAdmin.Técnico)?'btn-primary':'btn-light'" class="btn">Técnico</button>
                <button type="button" @click="paginaAdmin('apoyo')" :class="(pagAdmin.Apoyo)?'btn-primary':'btn-light'" class="btn">Apoyo</button>
              </div>
              </template>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
        <!-- card tabulador Docente -->      
        <div class="card card-default" id="tabDoc">
          <div class="card-header">
            <h3 class="card-title" @mouseover="hoverEdit('doc', true)" @mouseleave="hoverEdit('doc', false)">
              Docente 
              <a id="doc" @click.prevent="accion='editarTabDoc';" style="display:none;" href="#"><span class="badge">Editar</span><i class="fas fa-pen" aria-hidden="true"></i></a>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div> 
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <table id="tab2" class="table table-bordered">
                <thead>
                  <tr>
                    <th>Cargo</th>
                    <th>Salario base</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(tab, key) in pagDoc">
                    <template v-if="key=='Auxiliar Docente'">
                      <template v-for="(arrSal, dedic) in tab">
                        <tr v-for="(sal, ind) in arrSal">
                          <td>{{key+' '+parseInt(ind+1)+' '+dedic}}</td>
                          <template v-if="accion=='editarTabDoc'">
                            <td width="40%">
                              <div class="form-group row" style="margin:0;">
                                <div class="col-sm-4">
                                  <input :id="'input'+key+ind" @change="validarCampo(tabDoc[key][dedic][ind], 'input'+key+ind)" v-model="tabDoc[key][dedic][ind]" type="text" class="form-control form-control-sm">
                                </div>
                              </div>
                            </td>
                          </template>
                          <template v-else>
                            <td width="40%">{{formatoDivisa(sal)}}</td>
                          </template>
                        </tr>
                      </template>
                    </template>
                    <template v-else>
                      <tr v-for="(sal, dedic) in tab">
                        <td>{{key+' '+dedic}}</td>
                        <template v-if="accion=='editarTabDoc'">
                          <td width="40%">
                            <div class="form-group row" style="margin:0;">
                              <div class="col-sm-4">
                                <input :id="'input'+key" @change="validarCampo(tabDoc[key][dedic], 'input'+key)" v-model="tabDoc[key][dedic]" type="text" class="form-control form-control-sm">
                              </div>
                            </div>
                          </td>
                        </template>
                        <template v-else>
                          <td width="40%">{{formatoDivisa(sal)}}</td>
                        </template>
                      </tr>
                    </template>
                  </template>
                </tbody>
              </table>
              <template v-if="accion=='editarTabDoc'">
                <div class="col col-md-3 mt-2">
                  <button @click="actualizarTab(tabDoc_id, tabDoc)" type="button" class="btn btn-primary">Guardar cambios</button>
                </div>
                <div class="col col-md-2 mt-2">
                  <button @click.prevent="accion='listar'; tabuladores()" type="button" class="btn btn-light">Descartar</button>
                </div>
              </template>
              <template v-else>
                <div class="col col-md-6 btn-group mt-2" role="group">
                  <button type="button" @click="paginaDoc('instructor')" :class="(pagDoc.Instructor)?'btn-primary':'btn-light'" class="btn">Instructor</button>
                  <button type="button" @click="paginaDoc('asistente')" :class="(pagDoc.Asistente)?'btn-primary':'btn-light'" class="btn">Asistente</button>
                  <button type="button" @click="paginaDoc('agregado')" :class="(pagDoc.Agregado)?'btn-primary':'btn-light'" class="btn">Agregado</button>
                  <button type="button" @click="paginaDoc('asociado')" :class="(pagDoc.Asociado)?'btn-primary':'btn-light'" class="btn">Asociado</button>
                  <button type="button" @click="paginaDoc('titular')" :class="(pagDoc.Titular)?'btn-primary':'btn-light'" class="btn">Titular</button>
                  <button type="button" @click="paginaDoc('auxiliar')" :class="(pagDoc['Auxiliar Docente'])?'btn-primary':'btn-light'" class="btn">Auxiliar</button>
                </div>
              </template>
            </div>  
            
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
        <!-- card tabulador Obrero -->      
        <div class="card card-default" id="tabObre">
          <div class="card-header">
            <h3 class="card-title" @mouseover="hoverEdit('obre', true)" @mouseleave="hoverEdit('obre', false)">
              Obrero  
              <a id="obre" @click.prevent="accion='editarTabObre';" style="display:none;" href="#"><span class="badge">Editar</span><i class="fas fa-pen" aria-hidden="true"></i></a>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div> 
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <table id="tab3" class="table table-bordered">
                <thead>
                <tr>
                  <th>Cargo</th>
                  <th>Salario base</th>
                </tr>
                </thead>
                <tbody>
                  <tr v-for="(sal, key) in tabObre">
                    <td>{{'Obrero Grado '+key}}</td>
                    <template v-if="accion=='editarTabObre'">
                      <td width="40%">
                        <div class="form-group row" style="margin:0;">
                          <div class="col-sm-4">
                            <input :id="'input'+key" @change="validarCampo(tabObre[key], 'input'+key)" v-model="tabObre[key]" type="text" class="form-control form-control-sm">
                          </div>
                        </div>
                      </td>
                    </template>
                    <template v-else>
                      <td width="40%">{{formatoDivisa(sal)}}</td>
                    </template>
                  </tr>
                </tbody>
              </table>
              <template v-if="accion=='editarTabObre'">
                <div class="col col-md-3 mt-2">
                  <button @click="actualizarTab(tabObre_id, tabObre)" type="button" class="btn btn-primary">Guardar cambios</button>
                </div>
                <div class="col col-md-2 mt-2">
                  <button @click.prevent="accion='listar'; tabuladores()" type="button" class="btn btn-light">Descartar</button>
                </div>
              </template>
            </div>  

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
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
            tabAdmin:{
               "Apoyo": [],
               "Técnico": [],
               "Profesional": []
            },
            tabAdmin_id:'',
            tabDoc:{
              "Instructor":{
                  "Exclusiva": 0,
                  "Tiempo Completo": 0,
                  "Medio Tiempo": 0,
                  "Convencional 7 Horas": 0,
                  "Convencional 6 Horas": 0,
                  "Convencional 5 Horas": 0,
                  "Convencional 4 Horas": 0,
                  "Convencional 3 Horas": 0,
                  "Convencional 2 Horas": 0
              },
              "Asistente":{
                  "Exclusiva": 0,
                  "Tiempo Completo": 0,
                  "Medio Tiempo": 0,
                  "Convencional 7 Horas": 0,
                  "Convencional 6 Horas": 0,
                  "Convencional 5 Horas": 0,
                  "Convencional 4 Horas": 0,
                  "Convencional 3 Horas": 0,
                  "Convencional 2 Horas": 0
              },
              "Agregado":{
                  "Exclusiva": 0,
                  "Tiempo Completo": 0,
                  "Medio Tiempo": 0,
                  "Convencional 7 Horas": 0,
                  "Convencional 6 Horas": 0,
                  "Convencional 5 Horas": 0,
                  "Convencional 4 Horas": 0,
                  "Convencional 3 Horas": 0,
                  "Convencional 2 Horas": 0
              },
              "Asociado":{
                  "Exclusiva": 0,
                  "Tiempo Completo": 0,
                  "Medio Tiempo": 0,
                  "Convencional 7 Horas": 0,
                  "Convencional 6 Horas": 0,
                  "Convencional 5 Horas": 0,
                  "Convencional 4 Horas": 0,
                  "Convencional 3 Horas": 0,
                  "Convencional 2 Horas": 0
                  
              },
              "Titular":{
                  "Exclusiva": 0,
                  "Tiempo Completo": 0,
                  "Medio Tiempo": 0,
                  "Convencional 7 Horas": 0,
                  "Convencional 6 Horas": 0,
                  "Convencional 5 Horas": 0,
                  "Convencional 4 Horas": 0,
                  "Convencional 3 Horas": 0,
                  "Convencional 2 Horas": 0
              },
              "Auxiliar Docente":{
                  "Exclusiva":[0, 0, 0],
                  "Tiempo Completo":[0, 0, 0],
                  "Medio Tiempo":[0, 0, 0],
                  "Convencional 7 Horas":[0, 0, 0],
                  "Convencional 6 Horas":[0, 0, 0],
                  "Convencional 5 Horas":[0, 0, 0],
                  "Convencional 4 Horas":[0, 0, 0],
                  "Convencional 3 Horas":[0, 0, 0],
                  "Convencional 2 Horas":[0, 0, 0]
              }
            },
            tabDoc_id:'',
            tabObre:{},
            tabObre_id:'',
            pagAdmin:{},
            pagDoc:{},
            accion:'listar',
            errors:[]
          }
        },
        methods: {  
          tabuladores(){
            let url = '/tabuladores';
            axios.get(url).then((response)=>{
              this.tabAdmin = JSON.parse(response.data.tabuladores[0].tabulador);
              this.tabAdmin_id = response.data.tabuladores[0].id;
              this.tabObre = JSON.parse(response.data.tabuladores[1].tabulador);
              this.tabObre_id = response.data.tabuladores[1].id;
              this.tabDoc = JSON.parse(response.data.tabuladores[2].tabulador);
              this.tabDoc_id = response.data.tabuladores[2].id;

              this.paginaAdmin('profesional');
              this.paginaDoc('instructor');

            }).catch((error)=>{
              console.log(error);
            });
          },
          paginaAdmin(pag){
            let me = this;
            switch(pag){
              case 'profesional':
                me.pagAdmin = {Profesional:me.tabAdmin.Profesional}; 
              break;
              case 'tecnico':
                me.pagAdmin = {Técnico:me.tabAdmin.Técnico};
              break;
              case 'apoyo':
                me.pagAdmin = {Apoyo:me.tabAdmin.Apoyo};
              break;
            };
          },
          paginaDoc(pag){
            let me = this;

            switch(pag){
              case 'instructor':
                me.pagDoc = {Instructor:me.tabDoc.Instructor}; 
              break;
              case 'asistente':
                me.pagDoc = {Asistente:me.tabDoc.Asistente};
              break;
              case 'agregado':
                me.pagDoc = {Agregado:me.tabDoc.Agregado};
              break;
              case 'asociado':
                me.pagDoc = {Asociado:me.tabDoc.Asociado};
              break;
              case 'titular':
                me.pagDoc = {Titular:me.tabDoc.Titular};
              break;
              case 'auxiliar':
                me.pagDoc = {'Auxiliar Docente':me.tabDoc['Auxiliar Docente']};
              break;
            };
          },
          formatoDivisa(number){
           let monto = new Intl.NumberFormat('en-US').format(number);
           return monto;
          },
          hoverEdit(id, display){
            let element = document.getElementById(id);
            element.style.display=(display)?'inline':'none';
          },
          validarCampo(campo, id){
            let element = document.getElementById(id);

            if (campo>0 && campo != 0 && !isNaN(campo)) {
              element.classList.remove('is-invalid');
              element.classList.add('is-valid');
              //Verifica si existe el indice
              if (this.errors.includes(element.id)) {
                this.errors.splice(element.id, 1);
              };
            }else{
              element.classList.add('is-invalid');
              if (!this.errors.includes(element.id)) {
                this.errors.push(element.id);
              };
            };
          },
          actualizarTab(tab, data){
            let me = this;

            if (!me.errors.length) {
              let url = '/tabuladores/update/'+tab;

              axios.put(url, {data}).then((response)=>{
                swal.fire(
                      'Actualizado exitosamente',
                      '',
                      'success');

                    me.accion = "listar";
                    me.tabuladores();
              }).catch((error)=>{
                console.log(error);
              })
            };
          }
        }, 
        mounted() {
          this.tabuladores();
        }
    }
</script>