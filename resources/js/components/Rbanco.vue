<template>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Datos bancarios</h3>
            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div> 
        <!-- /.card-header -->
        <div class="card-body">  
            <div class="row">    
                <div class="col-md-4 mb-2 form-group">
                    <label for="banco">Banco</label>
                    <select v-model="banco" id="banco" class="form-control">
                        <template v-for="banco in arrayBancos">
                           <option :key="banco.id" :value="banco.codigo+'-'">{{`${banco.codigo}- ${banco.nombre}`}}</option> 
                        </template>
                    </select>
                </div>
                <div class="col-md-4 mb-2 form-group">
                    <label for="numero_cuenta">Número de cuenta</label>
                    <button type="button" @click="cambiarCondicion()" class="btn btn-light btn-sm">-</button>
                    <input v-bind:maxlength="(separador)?23:20" @keyup="validarNum()" v-model="numero_cuenta" type="text" :class="['form-control', error]" id="numero_cuenta" required>
                    <div class="invalid-feedback">
                        *Este campo es requerido
                    </div>
                </div>
                <div class="col-md-12 mt-2 form-group">
                    <div class="form-check">
                        <input @change="validarForm(numero_cuenta)" class="form-check-input" v-model.number="tipo_cuenta" name="tipo" id="corriente" value="1" type="radio">
                        <label class="form-check-label" for="#corriente">
                            Corriente
                        </label>
                    </div>
                    <div class="form-check">
                        <input @change="validarForm(numero_cuenta)" class="form-check-input" v-model.number="tipo_cuenta" name="tipo" id="ahorro" value="0" type="radio">
                        <label class="form-check-label" for="#ahorro">
                            Ahorro
                        </label>
                    </div>
                </div>
            </div>  
        </div>
        <!-- /.card-body -->
    </div>
</template>
    
<script>
export default {
    props:{
        datosBancarios: '',
        id: '',
    },
    data(){
        return {
            //0102-0479-81-0000083645
            arrayBancos:[],
            banco: "0102-",
            numero_cuenta: "",
            tipo_cuenta: 1,
            separador: false,
            error: "",
        }
    },
    computed:{
        datos(){
            return {
                banco: this.banco.slice(0, 4),
                numero_cuenta: this.numero_cuenta,
                tipo_cuenta: this.tipo_cuenta,
            }
        }
    },
    methods: {
        listarBancos(){
            const url = '/bancos';
            let me = this;
            axios.get(url).then((response)=>{
                me.arrayBancos = response.data.bancos;
            }).catch(function(error){
                    Vue.toasted.error('Error inesperado', {duration:2000});
                    console.log(error);
            }); 
        },
        validarNum(){
            let num = this.numero_cuenta;

            const regExp = /[^0-9-]/g;

            num = num.replaceAll('--', '');
            this.numero_cuenta = num.replaceAll(regExp, '');


            if(this.separador){
                this.agregarGuion(num);
            }else if((num.slice(0, 4) != this.banco.slice(0, 4)) && num.length > 5){
                this.numero_cuenta = this.banco.slice(0, 4) + num;
            };

            this.validarForm(this.numero_cuenta);
        },
        
        agregarGuion(num){

            if(!num.includes(this.banco) && num.length > 5){

                if(num.slice(0, 4) == this.banco.slice(0, 4)){
                    
                    let cadena = this.numero_cuenta;
                    //Separo la cadena y añado el guion 
                    let formato = cadena.slice(0, 4) + '-';

                    let restante = cadena.slice(4);

                    this.numero_cuenta = formato+restante;
                }else{
                    this.numero_cuenta = this.banco + this.numero_cuenta;
                    //Asegura que num.length no supere el maximo de 22
                    this.numero_cuenta = this.numero_cuenta.slice(0, 21);
                }

                
            }

             //  Añade el primer guion
            if(num[9] && num[9] != '-'){
                let cadena = this.numero_cuenta;
                //Separo la cadena y añado el guion 
                let formato = cadena.slice(0, 9) + '-';

                let restante = cadena.slice(9);

                this.numero_cuenta = formato+restante;
            }

            //Añade el segundo guion
            if(num[12] && num[12] != '-'){
                let cadena = this.numero_cuenta;
                //Separo la cadena y añado el guion 
                let formato = cadena.slice(0, 12) + '-';

                let restante = cadena.slice(12);

                this.numero_cuenta = formato+restante;
            }

        },
        cambiarCondicion(){
            this.separador = !this.separador;
            if(this.separador){
                this.agregarGuion(this.numero_cuenta);
            }else{
                this.quitarGuion();
            }
        },
        quitarGuion(){
            let num = this.numero_cuenta;
            this.numero_cuenta = num.replaceAll('-', '');
        },
        validarForm(num){
            if(this.separador){
                this.error = (num.length == 23)?'is-valid':'is-invalid'
            }else{
                this.error = (num.length == 20)?'is-valid':'is-invalid'
            }

            if(this.error == 'is-valid'){
                this.datosBancarios(true, this.datos)
            }else{
                this.datosBancarios(false, this.datos)
            }
        },
        editarBanco(id){
            let url = 'empleado/banco/' + id;

            axios.get(url).then((response) =>{
                let cuenta = response.data.cuenta;
                let banco = response.data.banco;
                this.numero_cuenta = cuenta.numero_cuenta;
                this.tipo_cuenta = cuenta.tipo_cuenta;
                this.banco = banco.codigo+'-';

                this.datosBancarios(true, this.datos)

            }).catch((error) => {

                console.log(error)
            })
        },

    },
    mounted(){
        this.listarBancos();
    }
};
</script>
