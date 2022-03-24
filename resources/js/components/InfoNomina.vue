<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button @click="generarTxt()" class="btn btn-success">*.txt</button>
                    <!-- <button class="btn btn-success">*.csv</button> -->
                </div>
                <div class="card-body">
                 <!--  <div class="form-row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <a href="#" ><i aria-hidden="true" class='fa fa-search'></i></a>
                                    </div>
                                </div>
                                <input id="search" type="text" class="form-control" placeholder="Busqueda">
                            </div>
                        </div>
                    </div> -->
                    <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">Apellido</th>
                          <th scope="col">Cédula</th>
                          <th scope="col">Salario tabla</th>
                          <th scope="col">Salario Normal</th>
                          <template v-for="(encabezado, index) in encabezados.asignaciones">
                            <th scope="col" colspan="8" :key="index">
                              {{encabezado}}
                            </th>
                          </template>
                          <th scope="col">Total Primas</th>
                          <template v-for="encabezado in encabezados.retenciones">
                            <th scope="col" :key="encabezado">
                              {{encabezado}}
                            </th>
                          </template>
                          <th scope="col">Total Retención</th>
                          <template v-for="encabezado in encabezados.aportes">
                            <th scope="col" :key="encabezado">
                              {{encabezado}}
                            </th>
                          </template>
                          <th scope="col">Total Aportes</th>
                          <template v-for="encabezado in encabezados.descuentos">
                            <th scope="col" :key="encabezado">
                              {{encabezado}}
                            </th>
                          </template>
                          <th scope="col">Total Descuentos</th>
                          <th scope="col">Neto Abonar</th>
                          <!-- <th scope="col">Acción</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(pago, index) in arrayPagosNomina" :key="pago.id">
                          <th scope="row">{{index+1}}</th>
                          <td v-text="pago.nombre"></td>
                          <td v-text="pago.apellido"></td>
                          <td v-text="pago.pre_cedula + pago.cedula"></td>
                          <td v-text="formato(pago.salario)"></td>
                          <td v-text="formato(parseFloat(pago.salarioNormal))"></td>
                          <template v-for="asign in pago.encabezados.asignaciones">
                            <td scope="col" colspan="8" :key="asign">
                              {{imprimir(pago.primas, asign)}}
                            </td>
                          </template>
                          <td>
                            <strong v-text="formato(pago.totalPrimas)"></strong>
                          </td>
                          <template v-for="(reten, indexReten) in pago.encabezados.retenciones">
                            <td scope="col" :key="indexReten">
                              {{imprimir(pago.retenciones, reten)}}
                            </td>
                          </template>
                          <td>
                            <strong v-text="formato(pago.totalRetencion)"></strong>
                          </td>
                          <template v-for="(dato, indexAporte) in pago.encabezados.aportes">
                            <td scope="col" :key="indexAporte">
                              {{imprimir(Object.values(pago.aportes), dato)}}
                            </td>
                          </template>
                          <td>
                            <strong v-text="formato(pago.totalAportes)"></strong>
                          </td>
                          <template v-for="dato in pago.encabezados.descuentos">
                            <td scope="col" :key="dato">
                              {{imprimir(pago.descuentos, dato)}}
                            </td>
                          </template>
                          <td>
                            <strong v-text="formato(pago.totalDescuentos)"></strong>
                          </td>
                          <td v-text="formato(pago.netoAbonar)"></td>
                          <!-- <td>
                            <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
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
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        id: Number,
    },
    data(){
        return {
            arrayPagosNomina: [],
            encabezados: [],
            pagination: {
                "total": 0,
                "current_page": 0,
                "per_page": 0,
                "last_page": 0,
                "from": 0,
                "to": 0
            },
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
    methods:{
        listarDatos(id){
            const url = '/nominas/'+id;

            axios.get(url).then((response)=>{
              this.arrayPagosNomina = response.data.pagos;
              this.encabezados = response.data.encabezados;
            }).catch((error)=>{
              alert(error);
            })
        },
        cambioPagina(page){
            this.pagination.current_page = page;
            this.listarDatos(page, this.busqueda);
        },
        formato(number){
           let monto = new Intl.NumberFormat('de-DE').format(number);
           return monto;
        },
        generarTxt(){
          window.open('/nominas/txt/'+this.id);
        },
        imprimir(dato, buscado){
          
          if(!Array.isArray(dato)){
            dato = Object.values(dato)
          }

          const datoFiltrado = dato.filter(e => e.concepto == buscado)[0] ?? 0;

          return (datoFiltrado)? datoFiltrado.valor : 0;
          
        }
    },
    mounted(){
      this.listarDatos(this.id);
    }
}
</script>