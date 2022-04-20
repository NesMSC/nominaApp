<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol v-if="accion=='listar'" class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Nóminas</a></li>
            </ol>
            <ol v-if="accion=='consultar'" class="breadcrumb">
              <li class="breadcrumb-item"><a @click.prevent="accion = 'listar'" href="#">Nóminas</a></li>
              <li class="breadcrumb-item active">Consultar</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div v-if="accion == 'listar'" class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button @click="getTipos()" data-toggle="modal" data-target="#Modal" type="button" class="btn btn-light">
                  <i class="fa fa-plus"></i>&nbsp;Nuevo
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-3">
                    <div class="input-group">
                      <select @change="filtrarNominas(criterio)" class="form-control" v-model="criterio">
                        <option value="">Todas</option>       
                        <option value="NA-">Administrativo</option>
                        <option value="ND-">Docente</option>
                        <option value="NO-">Obrero</option>
                        <option value="NV-">Vigilantes</option>
                      </select>
                    </div>
                  </div>
                </div>
                <table class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>

                    <tr v-for="nomina in nominas" :key="nomina.id">
                      <td v-text="nomina.codigo"></td>
                      <td v-text="nomina.tipo"></td>
                      <td v-text="nomina.descripcion"></td>
                      <td v-text="nomina.fecha"></td>
                      <td>
                        <a href="#" class="btn btn-success btn-sm" @click.prevent="consultar(nomina.id)"><i class="fa fa-table" aria-hidden="true"></i></a>
                        <a href="#" class="btn btn-danger btn-sm text-light" @click.prevent="eliminar(nomina.id, nomina.codigo)"><i class="fas fa-trash"></i></a>
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
      <div v-if="accion == 'consultar'" class="container-fluid">
        <infoNomina :id="id_nomina"></infoNomina>
      </div>
    </section>
    <!-- /.content -->

    <!-- Modal nueva nomina -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabel">Nueva nómina</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="tipo" class="col-form-label">Personal</label>
                <select @change="validarinput('personal', personal)" v-model="personal" id="personal" name="personal" :class="['form-control', validPersonal]" required>
                  <option disabled selected>Seleccionar</option>
                  <option value="Administrativo">Administrativo</option>
                  <option value="Docente">Docente</option>
                  <option value="Obrero">Obrero</option>
                  <option value="Vigilante">Vigilante</option>
                </select>
              </div>
              <div class="form-group">
                <label for="tipo" class="col-form-label">Tipo de nómina</label>
                <select @change="validarinput('tipo_nomina', tipo_nomina)" v-model="tipo_nomina" id="tipo_nomina" name="tipo_nomina" :class="['form-control', validType]" required>
                  <option disabled selected>Seleccionar</option>
                  <template v-for="tipo in tiposNomina">
                    <option :key="tipo.id" :value="tipo.name" v-text="tipo.name"></option>
                  </template>
                </select>
              </div>
              <div class="form-group">
                <label for="descripcion" class="col-form-label">Descripción</label>
                <textarea @change="validarinput('descripcion', descripcion)" v-model="descripcion" :class="['form-control', validText]" id="descripcion"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <clip-loader :color="'#007bff'" :loading="loader" :size="'25px'"></clip-loader>
            <button v-show="!loader" @click="registrar()" type="button" :class="['btn', 'btn-primary']">Crear</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal nueva nomina -->
  </div>
<!-- /.content-wrapper -->
</template>
<script>
    export default {
        data() {
          return {
            accion: 'listar',
            id_nomina: 1,
            arrayNominas: [],
            nominas: [],
            tiposNomina: [],
            tipo_nomina: 'Seleccionar',
            personal: 'Seleccionar',
            descripcion: '',
            pagination: {
                "total": 0,
                "current_page": 1,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
              },
            criterio: '',
            fecha: new Date(),
            validType: '',
            validText: '',
            validPersonal: '',
            loader:false,
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
          listarNominas(page = 1){
            let me = this;
            var url= '/nominas?page='+page;
            axios.get(url).then(function (response) {
                var respuesta= response.data.nominas.data;
                me.arrayNominas = respuesta;
                me.nominas = me.arrayNominas;
                me.pagination = response.data.pagination;
            }).catch(function (error) {
                console.log(error);
            });
          },
          filtrarNominas(criterio){
            if(criterio){              
              this.nominas = this.arrayNominas.filter(data => data.codigo.includes(criterio));
            }else{
              this.listarNominas();
            }
          },
          registrar(){
            if(!this.validarForm()){
              return;
            }
            let me = this;
            me.loader = true;
            swal.fire({
                title: 'Se registrarán las nóminas correspondientes a la '+me.numNomina('mes'),
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                  let url = '/nominas/registrar';
                  axios.post(url, {
                    tipo_nomina:me.tipo_nomina,
                    descripcion:me.descripcion,
                    personal:me.personal,
                    quincena:me.numNomina('año')
                  }).then(function(resp){
                      if(resp.data.status == 'success'){
                        swal.fire(
                          resp.data.msg,
                          '',
                          'success'
                        )
                        $('#Modal').modal('hide');
                        me.loader = false;
                        me.resetearInputs();
                        me.listarNominas();
                    }else{
                      Vue.toasted.error(resp.data.msg, {duration:2000});
                      me.loader = false;
                    }
                  }).catch(function(error){
                    me.loader = false;
                    console.log(error)
                  });
                }else{
                  me.loader = false;
                };
              })
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarNominas();
          },
          numNomina(tipo){
            let me = this;
                const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            switch(tipo){
              case 'mes':
                return (me.fecha.getDate() <= 15)? `primera quincena de ${meses[me.fecha.getMonth()]}`:`segunda quincena de ${meses[me.fecha.getMonth()]}`;
              break;
              case 'año':
                let num = 0;
                for (var i = 0; i <= me.fecha.getMonth(); i++) {
                  num+=2;
                }
                num = (me.fecha.getDate() <= 15)? num-1 : num;
                return num;
              break;
            }
          },
          validarinput(input, value){

            switch(input){
              case 'personal':
                  this.validPersonal = (value != 'Seleccionar')? 'is-valid' : 'is-invalid';
                break;
              case 'tipo_nomina':
                this.validType = (value != 'Seleccionar')? 'is-valid' : 'is-invalid';
                break;
              case 'descripcion':
                this.validText = (value != '')? 'is-valid' : 'is-invalid';
                break;
            }

          },
          validarForm(){
            
            this.validarinput('personal', this.personal);
            this.validarinput('tipo_nomina', this.tipo_nomina);
            this.validarinput('descripcion', this.descripcion);

            if(this.validText == 'is-valid' && this.validType == 'is-valid' && this.validPersonal == 'is-valid'){
              return true;
            };
          },
          resetearInputs(){
            this.personal = 'Seleccionar';
            this.tipo_nomina = 'Seleccionar';
            this.descripcion = '';
            this.validPersonal = '';
            this.validText = '';
            this.validType = '';

          },
          consultar(id){
            this.id_nomina = id;
            this.accion = 'consultar'
          },
          eliminar(id, code){
            let me = this;
            let url = '/nominas/delete/'+id;
            swal.fire({
              title: `¿Seguro que desea eliminar ${code}?`,
              text: "Se eliminarán todos los pagos relacionados a esta nómina",
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
                        'Nomina eliminada',
                        '',
                        'success');
                  me.listarNominas();
                }).catch(function(error){
                  Vue.toasted.error('Error inesperado', {duration:2000});
                  console.log(error);
                });
              }
            });   
          },
          getTipos(){
            const url = '/tipos/all';

            axios.get(url).then(response => {
              this.tiposNomina = response.data;
            }).catch(e => console.log(e))
          }
        }, 
        mounted() {
          this.listarNominas();
        }
    }
</script>