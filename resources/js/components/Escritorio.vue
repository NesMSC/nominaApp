<template>
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Escritorio</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container">
        <div class="row">
          <!-- chartComponent -->
          <div class="col-6">
            <div class="row justify-content-center">
              <div class="col-8">
                  <chartComponent 
                  :width="30" 
                  :height="30"
                  :chartData="dataPoints"
                ></chartComponent>
              </div>
              
            </div>
              
          </div>
          <div class="col-6">
              <!-- Small boxes -->
            <div class="row justify-content-center">
              <div class="col-8">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3 v-text="numAdmin"></h3>

                    <p>Administrativos</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-8">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3 v-text="numDoc"></h3>

                    <p>Docentes</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-8">
                <!-- small box -->
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3 v-text="numObre"></h3>

                    <p>Obreros</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>

        <div class="row">
          <!-- ./col -->
          <div class="col-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Indicadores Económicos</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Salario minimo</th>
                    <th>Cesta ticket</th>
                    <th>U.T</th>
                    <th>Gaceta</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="indicador in indicadores" :key="indicador.id">
                      <template v-if="accion=='editar'">
                        <td>
                          <div class="form-group row" style="margin:0;">
                            <div class="col-sm-8">
                              <input v-model="salarioMin" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="form-group row" style="margin:0;">
                            <div class="col-sm-8">
                              <input v-model.number="cestaTicket" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="form-group row" style="margin:0;">
                            <div class="col-sm-8">
                              <input v-model="ut" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="form-group row" style="margin:0;">
                            <div class="col-sm-8">
                              <input v-model="gaceta" type="text" class="form-control form-control-sm">
                            </div>
                          </div>
                        </td>
                        <td>{{indicador.fecha}}<strong>-</strong> <span class="text-success">Actual</span></td>
                        <td><a class="btn btn-primary" @click.prevent="guardarInd(indicador.id)" href="#">Guardar</a></td>
                      </template>
                      <template v-else>
                        <td v-text="formato(indicador.salarioMin)"></td>
                        <td v-text="formato(indicador.cestaTicket)"></td>
                        <td v-text="formato(indicador.UnTributaria)"></td>
                        <td v-text="indicador.gaceta"></td>
                        <td>{{indicador.fecha}}<strong>-</strong> <span class="text-success">Actual</span></td>
                        <td><a class="btn btn-success" @click.prevent="accion='editar'" href="#"><i class="fas fa-pen" aria-hidden="true"></i></a></td>
                      </template>
                    </tr>
                  </tbody>
                </table>
                
                  <ul class="pagination btn-group mr-2 mt-4" role="group" aria-label="First group">
                    <li>
                      <button type="button" class="btn">1</button>
                    </li>
                  </ul>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- ./col -->
        </div>
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<!--     <section>
      <div class="row justify-content-center">
        <div class="col-6">
          <chartIndicadores></chartIndicadores>
        </div>
      </div>
    </section> -->
  </div>
  
</template>
<script>
    export default {
        data() {
          return {
            accion: '',
            numAdmin: 0,
            numObre: 0,
            numDoc: 0,
            dataPoints: {},
            indicadores: [],
            salarioMin: 0,
            cestaTicket: 0,
            ut: 0,
            gaceta: '',
            fecha: ''
        }},
        methods: {
          contar(){
            let url = 'empleados/contar';
            axios.get(url).then((response)=>{
              this.numAdmin = response.data.admin;
              this.numObre = response.data.obrero;
              this.numDoc = response.data.docente;
              
              let total = this.numAdmin + this.numObre + this.numDoc;

              this.dataPoints = {
                labels: ['Administrativo', 'Docente', 'Obrero'],
                datasets: [{
                    backgroundColor: [
                    '#17a2b8',
                    '#28a745',
                    '#ffc107',
                  ],
                  data: [this.numAdmin, this.numDoc, this.numObre].map(e => ((e*100)/total).toFixed(2))
                }]
              }
            }).catch((error)=>{
              console.log(error);
            });
          },
          indEconomicos(){
            axios.get('/indicadores').then((response)=>{
              this.indicadores = response.data.indicadores;
              this.salarioMin = response.data.indicadores[0].salarioMin;
              this.ut = response.data.indicadores[0].UnTributaria;
              this.gaceta = response.data.indicadores[0].gaceta;
              this.fecha = response.data.indicadores[0].fecha;
            }).catch((error)=>{
              console.log(error);
            });
          },
          guardarInd(id){
            let url = '/indicadores/editar';
            console.log(id)
            let fecha = new Date;
            axios.put(url, {
                salarioMin: this.salarioMin,
                cestaTicket: this.cestaTicket,
                ut: this.ut,
                gaceta: this.gaceta,
                fecha: `${fecha.getFullYear()}-${(fecha.getMonth()+1)}-${fecha.getDate()}`,
                id_ind: id
            }).then((response)=>{
              swal.fire(
                      'Actualizado exitosamente',
                      '',
                      'success');

              this.accion = "";
              this.indEconomicos();
            }).catch((error)=>{
              console.log(error);
            });
          },
          formato(number){
            let monto = new Intl.NumberFormat('en-US').format(number);
            return monto;
          },
        },
        mounted() {
          this.contar();
          this.indEconomicos();
        }
    };
</script>