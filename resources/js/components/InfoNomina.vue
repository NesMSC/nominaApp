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
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">Apellido</th>
                          <th scope="col">Cédula</th>
                          <th scope="col">Salario tabla</th>
                          <th scope="col">Salario Normal</th>
                          <th scope="col">Total Primas</th>
                          <th scope="col">Total Retención</th>
                          <th scope="col">Total Aportes</th>
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
                          <td v-text="pago.cedula"></td>
                          <td v-text="formato(pago.salario)"></td>
                          <td v-text="formato(parseFloat(pago.salarioNormal).toFixed(0))"></td>
                          <td v-text="formato(pago.totalPrimas.toFixed(0))"></td>
                          <td v-text="formato(pago.totalRetencion.toFixed(0))"></td>
                          <td v-text="formato(pago.totalAportes.toFixed(0))"></td>
                          <td v-text="formato(pago.totalDescuentos.toFixed(0))"></td>
                          <td v-text="formato(pago.netoAbonar.toFixed(0))"></td>
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
            }).catch((error)=>{
              alert(error);
            })
        },
        cambioPagina(page){
            this.pagination.current_page = page;
            this.listarDatos(page, this.busqueda);
        },
        formato(number){
           let monto = new Intl.NumberFormat('en-US').format(number);
           return monto;
        },
        generarTxt(){
          window.open('/nominas/txt/'+this.id);
        }
    },
    mounted(){
      this.listarDatos(this.id);
    }
}
</script>