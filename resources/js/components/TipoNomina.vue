<template>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Nóminas</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section v-if="!agregar" class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button @click="agregar = true" type="button" class="btn btn-light">
                                    <i class="fa fa-plus"></i>&nbsp;Nuevo
                                </button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                <a href="#" ><i aria-hidden="true" class='fa fa-search'></i></a>
                                                </div>
                                            </div>
                                            <input v-model="search" @keyup="listar(pagination.current_page, search)" id="search" type="text" class="form-control" placeholder="Busqueda">
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(tipo, index) in tiposNomina" :key="tipo.id">
                                            <td>{{index + 1}}</td>
                                            <td v-text="tipo.name"></td>
                                            <td v-text="tipo.description"></td>
                                            <td>
                                                <a href="#" @click.prevent="deleteTipo(tipo.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i></a>
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
        </section>
        <!-- /.content -->

        <section v-if="agregar || editar">
            <FormTipo :listar="listar"></FormTipo>
        </section>
    </div>
        <!-- /.content-wrapper -->
</template>

<script>
import FormTipo from './FormTipo.vue';
export default {
  components: { FormTipo },
    data(){
        return {
            tiposNomina: [],
            loader: false,
            agregar: true,
            editar: false,
            search: '',
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
        }
    },
    methods: {
        listar(page, search){
            this.agregar = false;

            const url = '/tipos?page='+page+'&busqueda='+search;

            axios.get(url).then(response => {
                this.tiposNomina = response.data.tipos.data;
                this.pagination = response.data.pagination;

            }).catch(e => console.log(e))
        },
        deleteTipo(id){
            const url = '/tipos/'+id;

            swal.fire({
              title: '¿Desea eliminar la nómina?',
              text: "",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continuar',
              cancelButtonText: 'Cancelar'
            }).then(response => {
                if(response){
                    axios.delete(url).then(() => {
                        this.listar(1, this.search)
                        swal.fire(
                        "Nómina eliminada",
                        '',
                        'success');
                    }).catch(e => console.log(e));
                }else return;
            })
            
        },
        cambioPagina(page){
            this.pagination.current_page = page;
            this.listar(page, this.busqueda);
        }  
    },
    mounted(){
        this.listar(1, this.search)
    }
}
</script>
