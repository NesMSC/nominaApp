<template>
    <div class="card">
        <div class="card-header">
            <h3>
                Nuevo tipo de Nómina
            </h3>
        </div>
        <div class="card-body">
            <form class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="name" class="col-form-label">Nombre</label>
                    <input placeholder="Nómina de pagos" v-model="nomina.name" type="text" class="form-control" id="name">
                </div>
                <div class="form-group col-md-8 col-sm-12">
                    <label for="descripcion" class="col-form-label">Descripción</label>
                    <textarea placeholder="(Opcional)"
                        v-model="nomina.description" 
                        :class="['form-control']" 
                        id="descripcion"
                    ></textarea>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-collapse collapsed-card">
                        <div class="card-header">
                            <div class="card-title">
                                Beneficios
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="beneficio in beneficios" :key="beneficio.id">
                                        <td v-text="beneficio.concepto"></td>
                                        <td v-text="beneficio.valor+' '+ beneficio.tipo_valor"></td>
                                        <td>
                                            <template v-if="existBeneficio(beneficio.id)">
                                                <a href="#" @click.prevent="retirarBeneficio(beneficio.id)">
                                                    <i class="fa fa-check text-success"></i>
                                                </a>
                                            </template>
                                            <template v-else>
                                                <a href="#" @click.prevent="insertBeneficio(beneficio.id)">
                                                    <i class="fa fa-plus text-dark"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-default card-collapse collapsed-card">
                        <div class="card-header">
                            <div class="card-title">
                                Retenciones
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="retencion in retenciones" :key="retencion.id">
                                        <td v-text="retencion.concepto"></td>
                                        <td v-text="retencion.porcentaje"></td>
                                        <td>
                                            <template v-if="existDescuento(retencion.id)">
                                                <a href="#" @click.prevent="retirarDescuento(retencion.id)">
                                                    <i class="fa fa-check text-success"></i>
                                                </a>
                                            </template>
                                            <template v-else>
                                                <a href="#" @click.prevent="insertDescuento(retencion.id)">
                                                    <i class="fa fa-plus text-dark"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-default card-collapse collapsed-card">
                        <div class="card-header">
                            <div class="card-title">
                                Aportes
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="aporte in aportes" :key="aporte.id">
                                        <td v-text="aporte.concepto"></td>
                                        <td v-text="aporte.porcentaje"></td>
                                        <td>
                                            <template v-if="existDescuento(aporte.id)">
                                                <a href="#" @click.prevent="retirarDescuento(aporte.id)">
                                                    <i class="fa fa-check text-success"></i>
                                                </a>
                                            </template>
                                            <template v-else>
                                                <a href="#" @click.prevent="insertDescuento(aporte.id)">
                                                    <i class="fa fa-plus text-dark"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-default card-collapse collapsed-card">
                        <div class="card-header">
                            <div class="card-title">
                                Descuentos
                            </div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="descuento in descuentos" :key="descuento.id">
                                        <td v-text="descuento.concepto"></td>
                                        <td v-text="descuento.porcentaje"></td>
                                        <td>
                                            <template v-if="existDescuento(descuento.id)">
                                                <a href="#" @click.prevent="retirarDescuento(descuento.id)">
                                                    <i class="fa fa-check text-success"></i>
                                                </a>
                                            </template>
                                            <template v-else>
                                                <a href="#" @click.prevent="insertDescuento(descuento.id)">
                                                    <i class="fa fa-plus text-dark"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <button @click="saveTipo()" type="button" class="btn btn-primary">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        listar: ""
    },
    data(){
        return {
            nomina: {
                name: '',
                description: '',
                beneficiosId: [],
                descuentosId: [],
            },
            beneficios: [],
            retenciones: [],
            aportes: [],
            descuentos: [],
        }
    },
    methods: {
        getConceptos(){
            const url = '/conceptos';

            axios.get(url).then(response => {
                this.beneficios = response.data.beneficios;
                this.retenciones = response.data.descuentos.filter(e => e.tipo == "Retención");
                this.aportes = response.data.descuentos.filter(e => e.tipo == "Aporte");
                this.descuentos = response.data.descuentos.filter(e => e.tipo == "Descuento");

            }).catch(e => {
                console.log(e);
            })
        },
        insertBeneficio(id){
            this.nomina.beneficiosId.push(id);
        },
        retirarBeneficio(id){
            const index = this.nomina.beneficiosId.indexOf(id);
            this.nomina.beneficiosId.splice(index, 1);
        },
        existBeneficio(id){
            return this.nomina.beneficiosId.some(e => e == id);
        },
        insertDescuento(id){
            this.nomina.descuentosId.push(id);
        },
        retirarDescuento(id){
            const index = this.nomina.descuentosId.indexOf(id);
            this.nomina.descuentosId.splice(index, 1);
        },
        existDescuento(id){
            return this.nomina.descuentosId.some(e => e == id);
        },
        saveTipo(){
            if(!this.nomina.name || !this.nomina.beneficiosId.length || !this.nomina.descuentosId.length){
                Vue.toasted.error( 'Datos incompletos', {duration:2000});
                return;
            }
            const url = '/tipos';

            axios.post(url, {...this.nomina})
                .then(() => {
                    swal.fire(
                        'Nómina agregada exitosamente',
                        '',
                        'success'
                      )
                      this.listar(1, '');
                })
                .catch(e => console.log(e));
        }
    },
    mounted(){
        this.getConceptos();
    }
}
</script>