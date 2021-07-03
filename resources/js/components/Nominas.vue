<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Nominas</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button @click="registrar()" type="button" class="btn btn-light">
                  <i class="fa fa-plus"></i>&nbsp;Nuevo
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-3">
                    <div class="input-group">
                      <select @change="listarNominas(1, criterio)" class="form-control" v-model="criterio">
                        <option value="Administrativo">Administrativo</option>
                        <option value="Docente">Docente</option>
                        <option value="Obrero">Obrero</option>
                      </select>
                    </div>
                  </div>
                </div>
                <table class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>n°</th>
                    <th>Quincena</th>
                    <th>Fecha</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="nomina in arrayNominas" :key="nomina.id">
                      <td v-text="nomina.id"></td>
                      <td v-text="nomina.quincena"></td>
                      <td v-text="nomina.fecha"></td>
                      <!-- <td>
                        <a href="#" style="color:#fff;" class="btn btn-success btn-sm" click.prevent=""><i class="fa fa-table" aria-hidden="true"></i></a>
                      </td> --> 
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
            arrayNominas: [],
            tipoRegistro: "Administrativo",
            pagination: {
                "total": 0,
                "current_page": 0,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
              },
            criterio:"Administrativo",
            fecha: new Date()
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
          listarNominas(page, criterio){
            let me = this;
            var url= '/nominas?page='+page+'&nomina='+criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data.nominas.data;
                me.arrayNominas = respuesta;
                me.pagination = response.data.pagination;
            }).catch(function (error) {
                console.log(error);
            });
          },
          registrar(){
            let me = this;
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
                    quincena:me.numNomina('año')
                  }).then(function(response){
                    swal.fire(
                        'Nominas generadas',
                        '',
                        'success'
                      )
                    me.listarNominas(1, me.criterio);
                  }).catch(function(error){
                    console.log(error)
                  });
                }else return;
              })
          },
          cambioPagina(page){
            this.pagination.current_page = page;
            this.listarNominas(page, this.criterio);
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
          }   
        }, 
        mounted() {
          this.listarNominas(1, this.criterio);
        }
    }
</script>