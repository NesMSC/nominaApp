<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-6">
                        <template v-if="accion=='listar'">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Salarios</a></li>
                            <li class="breadcrumb-item active">Bancos</li>
                        </ol>
                        </template>
                        <template v-if="accion=='registrar'">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Salarios</a></li>
                            <li class="breadcrumb-item"><a href="#" @click="accion='listar'; resetearInputs()">Bancos</a></li>
                            <li class="breadcrumb-item active">Agregar Banco</li>
                        </ol>
                        </template>
                        <template v-if="accion=='editar'">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Salarios</a></li>
                            <li class="breadcrumb-item"><a href="#" @click="accion='listar'; resetearInputs()">Bancos</a></li>
                            <li class="breadcrumb-item active">Editar Banco</li>
                        </ol>
                        </template>
                    </div>
                </div>
            </div>
        </section> 
        <section class="content">
            <div class="container-fluid" v-if="accion=='listar'">
                <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">
                        <button @click="accion='registrar'" type="button" class="btn btn-light">
                        <i class="fa fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="bancosTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="banco in arrayBancos" :key="banco.id">
                                    <td v-text="banco.nombre"></td>
                                    <td v-text="banco.codigo"></td>
                                    <td>
                                        <a 
                                            href="#" style="color:#fff;" 
                                            class="btn btn-success btn-sm" 
                                            @click.prevent="datosBanco(banco.id); accion='editar'"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a 
                                            href="#" 
                                            class="btn btn-danger btn-sm text-light" 
                                            @click.prevent="eliminarBanco(banco.id, banco.nombre)"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </a>
                                       
                                    </td>
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
            <div class="container-fluid" v-if="accion=='registrar' || accion=='editar'">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Agregar Banco</h3>
                    </div> 
                    <!-- /.card-header -->
                    <div class="card-body">  
                        <form>
                            <div class="form-row mt-2">
                                <div class="form-group col-md-4 col-sm-6">
                                    <label for="nombre">Nombre</label>
                                    <input v-model="nombreBanco" type="text" :class="['form-control', validNombre]" id="nombre" required placeholder="Nombre">
                                    <div class="invalid-feedback">
                                        *Este campo es requerido
                                    </div>
                                </div>
                                <div class="form-group col-md-2 col-sm-4">
                                    <label for="codigo">Codigo</label>
                                    <input v-model="codigo" type="number" :class="['form-control', validCodigo]" id="codigo" required placeholder="Codigo">
                                    <div class="invalid-feedback">
                                        *Este campo es requerido
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <button 
                                        @click.prevent="(accion=='registrar')? agregarBanco() : actualizarBanco()" 
                                        class="btn btn-primary"
                                    >Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </section>
    </div>
    
</template>
<script>
export default {
    data(){
        return{
            arrayBancos: [],
            id_banco: Number,
            nombreBanco: '',
            codigo: '',
            accion: 'listar',
            validNombre: '',
            validCodigo: '',
        }
    },
    methods:{
        listarBancos(){
            const url = '/bancos'
            axios.get(url).then((response)=>{
                this.arrayBancos = response.data.bancos;
            });
        },
        agregarBanco(){
            let me = this;
            const url = '/bancos';
            if(this.validarForm()){
               axios.post(url, {
                    nombre: me.nombreBanco,
                    codigo: me.codigo,
                }).then((response)=>{
                    swal.fire(
                            'Banco registrado exitosamente',
                            '',
                            'success'
                        );

                    me.accion = "listar";
                    me.listarBancos();
                    me.resetearInputs();
                }).catch(function(error){
                    Vue.toasted.error('Error inesperado', {duration:2000});
                    console.log(error);
                }); 
            }
            
        },
        datosBanco(id){
            const url = '/banco/'+id;
            let me = this;

            axios.get(url).then((response)=>{
                let banco = response.data.banco;
                me.nombreBanco = banco.nombre;
                me.codigo = banco.codigo;
                me.id_banco = banco.id;
            }).catch(function(error){
                Vue.toasted.error('Error inesperado', {duration:2000});
                console.log(error);
            });

        },
        actualizarBanco(){
            const url = '/bancos';
            let me = this;
            if(me.validarForm()){
              axios.put(url,{
                    nombre: me.nombreBanco,
                    codigo: me.codigo,
                    id: me.id_banco,
                }).then(()=>{
                    swal.fire(
                                'Banco actualzado exitosamente',
                                '',
                                'success'
                            );

                    me.accion = "listar";
                    me.listarBancos();
                    me.resetearInputs();
                }).catch(function(error){
                    Vue.toasted.error('Error inesperado', {duration:2000});
                    console.log(error);
                }) 
            };
            
        },
        eliminarBanco(id, name){
            let me = this;
            let url = '/banco/'+id;
            swal.fire({
              title: `¿Seguro que desea eliminar ${name}?`,
              text: "Se eliminarán todos las cuentas bancarias asociadas a este banco",
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
                        'Banco eliminado',
                        '',
                        'success');
                  me.listarBancos();
                }).catch(function(error){
                  Vue.toasted.error('Error inesperado', {duration:2000});
                  console.log(error);
                });
              }
            });   
        },
        validarForm(){
            let error = true;

            if(this.codigo == ''){
                this.validCodigo = 'is-invalid';
                error = false;
            }else{
                this.validCodigo = 'is-valid';
            };
            
            if(this.nombreBanco == ''){
                this.validNombre = 'is-invalid';
                error = false;
            }else{
                this.validNombre = 'is-valid';
            };

            return error;
        },
        resetearInputs(){
            this.nombreBanco = '';
            this.codigo = '';
        }
    },
    mounted(){
        this.listarBancos();
    }
}
</script>