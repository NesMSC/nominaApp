<template>
	<fragment>
		<!-- card datos de salario-->      
        <div class="card card-default">
            <div class="card-header">
              	<h3 class="card-title">Datos de Salario</h3>
	            <div class="card-tools">
	            	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
	            </div>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">  
              <div class="row">
                <table class="table table-bordered table-striped table-sm">
                  	<tbody>
	                    <tr style="background-color: #CEEFCF5">
	                      <td colspan="4">
	                        <strong>Salario tabla para
	                        	<template v-if="personal=='Administrativo'">
	                        		<span v-if="grado!='Seleccionar' && nivel!='Seleccionar'" v-text="grado+' '+nivel+':'"></span>
	                        	</template>
	                        	<template v-else-if="personal=='Docente'">
	                        		<span v-text="(categoria=='Auxiliar Docente')?`${categoria} ${gradoAuxiliar}`:categoria"></span>
	                        		<span v-text="(dedicacion=='Convencional')?`Dedicación ${dedicacion} ${tiempoCon} Horas`:`Dedicación ${dedicacion}`"></span>
	                        	</template>
	                        	<template v-else>
	                        		<span v-text="`Obrero grado ${grado}`"></span>
	                        	</template>
	                        </strong>
	                      </td>
	                      <td colspan="2">
	                        <span>
	                          <p  v-text="formatoDivisa(salarioTabla)"></p>
	                        </span>
	                        
	                      </td>
	                    </tr>
	                    <tr>
	                      <td colspan="4">
	                        <strong>Años de Servicio: </strong>
	                      </td>
	                      <td colspan="2">
	                        <span v-text="anos"></span>
	                      </td>
	                    </tr>
                  	</tbody>
                  	<tbody>
	                	<tr style="background-color: #343a40">
	                    	<td colspan="6" align="center" class="text-light">BENEFICIOS</td>
	                	</tr>
	                	<tr v-for="beneficio in beneficiosEmpleado" :key="beneficio.id">
	                    	<td colspan="4" v-text="beneficio.concepto"></td>
	                    	<td colspan="2" class="beneficio" v-text="formatoDivisa(beneficio.valor)"></td>
	                  	</tr>
	                  	<tr v-if="beneficiosEmpleado.length != 0">
	                    	<td colspan="4"><strong>Total de Beneficios:</strong></td>
	                    	<strong><td colspan="2" v-text="formatoDivisa(salarioNormal-salarioTabla)"></td></strong>
	                  	</tr> 
	                  	<tr>
	                    	<td colspan="6" align="center">
	                      		<button @click="listarBeneficios()" class="btn btn-light" data-toggle="modal" data-target="#ModalBeneficios"><i class="fa fa-plus"></i>&nbsp;Agregar/Quitar</button>
	                    	</td>
	                  	</tr>
                  	</tbody>                  
                  	<tbody>
	                    <tr style="background-color: #343a40">
	                      <td colspan="6" align="center" class="text-light">DEDUCCIONES</td>
	                    </tr>
	                    <tr v-for="deduccion in deduccionesEmpleado" :key="deduccion.id">
	                      <td colspan="4" v-text="deduccion.concepto"></td>
	                      <td colspan="2" v-text="formatoDivisa(deduccion.valor)"></td>
	                    </tr>
	                    <tr v-if="deduccionesEmpleado.length != 0">
	                      <td colspan="4"><strong>Total de Deducciones:</strong></td>
	                      <strong><td colspan="2" v-text="formatoDivisa(totalDeduc)"></td></strong>
	                    </tr>
	                    <tr >
	                      <td colspan="6" align="center">
	                        <button @click="listarDeducciones()" class="btn btn-light" data-toggle="modal" data-target="#ModalDeducciones"><i class="fa fa-plus"></i>&nbsp;Agregar/Quitar</button>
	                      </td>
	                    </tr>
                  	</tbody>
                  	<tbody>
	                    <tr style="background-color: #343a40">
	                      <td colspan="6" align="center" class="text-light">DESCUENTOS</td>
	                    </tr>
	                    <tr v-for="descuento in descuentosEmpleado" :key="descuento.id">
	                      <td colspan="4" v-text="descuento.concepto"></td>
	                      <td colspan="2" v-text="formatoDivisa(descuento.valor)"></td>
	                    </tr>
	                    <tr v-if="descuentosEmpleado.length != 0">
	                      <td colspan="4"><strong>Total de Descuentos:</strong></td>
	                      <strong><td colspan="2" v-text="formatoDivisa(totalDesc)"></td></strong>
	                    </tr>
	                    <tr >
	                      <td colspan="6" align="center">
	                        <button @click="listarDescuentos()" class="btn btn-light" data-toggle="modal" data-target="#ModalDescuentos"><i class="fa fa-plus"></i>&nbsp;Agregar/Quitar</button>
	                      </td>
	                    </tr>
                  	</tbody>
                  	<tbody>
	                    <tr style="background-color: #CEEFCF5">
	                      <td colspan="4"><strong>Salario normal mensual</strong></td>
	                      <td colspan="2" v-text="formatoDivisa(parseFloat(salarioNormal).toFixed(2))"></td>
	                    </tr>
	                    <tr style="background-color: #FFF !important">
	                      <td colspan="6">
	                        <div class="form-group form-check">
	                          <input @click="validarDatosSalario()" type="checkbox" class="form-check-input" id="confirm_sal">
	                          <label class="form-check-label" for="confirm_sal">Confirmo que he ingresado los datos salariales correctamente</label>
	                        </div>
	                      </td>
	                    </tr>
                  	</tbody>
                </table>
              </div>  
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            </div>
        </div>
          <!-- /.card -->
		<!-- Modal Beneficios-->
	    <div class="modal fade" id="ModalBeneficios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	      <div class="modal-dialog" role="document">
	        <div class="modal-content">
	          <div class="modal-body">
	            <div class="modal-body">
	              <div class="container-fluid">
	                <div class="row">
	                  <div class="col-md-12">
	                    <table class="table table-hover">
	                      <thead>
	                        <tr>
	                          <th>Beneficio</th>
	                          <th>Cantidad</th>
	                          <th>Estado</th>
	                        </tr>
	                      </thead>
	                      <tbody>
	                        <tr v-for="beneficio in arrayBeneficios" :key="beneficio.id">
	                          <td v-text="beneficio.concepto"></td>
	                          <td v-text="beneficio.valor+beneficio.tipo_valor"></td>
	                          <td>
	                            <template v-if="exist(beneficio.id, 'bene')">
	                              <a href="#" @click.prevent="retirarBeneficio(beneficio.id)">
	                                <i class="fa fa-check text-success"></i>
	                              </a>
	                            </template>
	                            <template v-else>
	                              <a href="#" @click.prevent="agregarBeneficio({id:beneficio.id,concepto:beneficio.concepto, tipo_valor:beneficio.tipo_valor, valor:beneficio.valor})">
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
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	          </div>
	        </div>
	      </div>
	    </div>
	    <!-- /.Modal -->
	    <!-- Modal Deducciones-->
	    <div class="modal fade" id="ModalDeducciones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	      <div class="modal-dialog" role="document">
	        <div class="modal-content">
	          <div class="modal-body">
	            <div class="modal-body">
	              <div class="container-fluid">
	                <div class="row">
	                  <div class="col-md-12">
	                    <table class="table table-hover">
	                      <thead>
	                        <tr>
	                          <th>Concepto</th>
	                          <th>Tipo</th>
	                          <th>Valor</th>
	                          <th>Estado</th>
	                        </tr>
	                      </thead>
	                      <tbody>
	                        <tr v-for="deduccion in arrayDeducciones" :key="deduccion.id">
	                          <td v-text="deduccion.concepto"></td>
	                          <td v-text="deduccion.tipo"></td>
	                          <td v-text="deduccion.porcentaje+'%'"></td>
	                          <td>
	                            <template v-if="exist(deduccion.id, 'deduc')">
	                              <a href="#" @click.prevent="retirarDeduccion(deduccion.id)">
	                                <i class="fa fa-check text-success"></i>
	                              </a>
	                            </template>
	                            <template v-else>
	                              <a href="#" @click.prevent="agregarDeduccion({id:deduccion.id, concepto:deduccion.concepto, valor:0, tipo:deduccion.tipo, porcentaje:deduccion.porcentaje})">
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
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	          </div>
	        </div>
	      </div>
	    </div>
	    <!-- /.Modal -->
	    <!-- Modal Descuentos-->
	    <div class="modal fade" id="ModalDescuentos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	      <div class="modal-dialog" role="document">
	        <div class="modal-content">
	          <div class="modal-body">
	            <div class="modal-body">
	              <div class="container-fluid">
	                <div class="row">
	                  <div class="col-md-12">
	                    <table class="table table-hover">
	                      <thead>
	                        <tr>
	                          <th>Concepto</th>
	                          <th>Tipo</th>
	                          <th>Valor</th>
	                          <th>Estado</th>
	                        </tr>
	                      </thead>
	                      <tbody>
	                        <tr v-for="descuento in arrayDescuentos" :key="descuento.id">
	                          <td v-text="descuento.concepto"></td>
	                          <td v-text="descuento.tipo"></td>
	                          <td v-text="descuento.porcentaje+'%'"></td>
	                          <td>
	                            <template v-if="exist(descuento.id, 'desc')">
	                              <a href="#" @click.prevent="retirarDescuento(descuento.id)">
	                                <i class="fa fa-check text-success"></i>
	                              </a>
	                            </template>
	                            <template v-else>
	                              <a href="#" @click.prevent="agregarDescuento({id:descuento.id,concepto:descuento.concepto, valor:0, tipo:descuento.tipo, porcentaje:descuento.porcentaje})">
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
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	          </div>
	        </div>
	      </div>
	    </div>
	    <!-- /.Modal -->
	</fragment>	
		
</template>
<script>

export default{
	props: {
		id : "",
		grado: "",
		nivel: "",
		gradoAuxiliar: "",
        categoria: "",
        dedicacion: "",
        tiempoCon: "",
		anos: 0,
		avisar: "",
		personal: "",
		idSalario: Number,
		instruccion: ""
	},
	data(){
		return {
            arrayBeneficios: [],
            arrayDescuentos: [],
            arrayDeducciones: [],
            salarioTabla: 0,
            beneficiosEmpleado: [],
            descuentosEmpleado: [],
            deduccionesEmpleado: [],
            id_beneficiosAgregados: [],
            id_deduccionesAgregados: [],
            id_descuentosAgregados: [],
            totalAsig: 0,
            salarioNormal: 0,
            totalDesc: Number,
            totalDeduc: Number,
            UT: 0,
		}
	},
	methods: {

      	listarBeneficios(){
		    let me = this;
		    let url = 'empleados/beneficios';
		    axios.get(url).then(function(response){
		      me.arrayBeneficios = response.data.beneficios;
		    }).catch(function(error){
		      console.log(error);
		    })
      	},
      	listarDescuentos(){
            let me = this;
            let url = 'empleados/descuentos';
            axios.get(url).then(function(response){
              me.arrayDescuentos = response.data.descuentos;
            }).catch(function(error){
              console.log(error);
            })
        },
        listarDeducciones(){
            let me = this;
            let url = 'empleados/deducciones';
            axios.get(url).then(function(response){
              me.arrayDeducciones = response.data.deducciones;
            }).catch(function(error){
              console.log(error);
            })
        },
        formatoDivisa(number){
           let monto = new Intl.NumberFormat('en-US').format(number);
           return monto;
        },
		datoSalario(){
			let me = this;
			

			if (me.grado!='Seleccionar' && me.nivel!='Seleccionar') {
	      		let url = 'empleados/salarioTabla';
		      	axios.post(url, {id_salario:me.idSalario}).then(function(response){
		      	let salario = JSON.parse(response.data.tabulador);
		      	switch(me.personal){
		      		case "Administrativo":
		      			let grado = me.grado;
			      		let nivel = parseInt(me.nivel)-1;
			      		salario = salario[grado][nivel];
		      		break;
		      		case "Docente":
		      			let categoria = me.categoria;
	                  	let horas = me.tiempoCon;
	                  	let dedicacion = me.dedicacion;
	                  	if (categoria=='Auxiliar Docente') {
	                    	salario = (dedicacion=='Convencional')?salario[categoria][`${dedicacion} ${horas} Horas`]:salario[categoria][dedicacion];
	                    	salario = salario[parseInt(me.gradoAuxiliar)-1];
	                  	}else{
	                    	salario = (dedicacion=='Convencional')?salario[categoria][`${dedicacion} ${horas} Horas`]:salario[categoria][dedicacion];
	                  	}
		      		break;
		      		case "Obrero":
		      			let gradoObrero = me.grado;
	                    salario = salario[gradoObrero];
		      		break;
		      	};
			      					    
			      me.salarioTabla = salario;
			      me.UT = response.data.UT;		    
			    }).catch(function(error){
			      console.log(error);
			    });
		  	}

		  	if(me.id !== ''){
				me.editar(me.id);
			}
		},
		editar(id){
			let me = this;
			let url = '/empleados/editarSalarios/'+id;
            axios.get(url).then(function(response){


              let beneficios = response.data.beneficios;
              let descuentos = response.data.descuentos;
              let deducciones = response.data.deducciones;
              
              for (var i = 0; i < beneficios.length; i++) {
                    me.agregarBeneficio({id:beneficios[i].id,concepto:beneficios[i].concepto, tipo_valor:beneficios[i].tipo_valor, valor:beneficios[i].valor})
                  };

              for (var i = 0; i < descuentos.length; i++) {
                    me.agregarDescuento({id:descuentos[i].id,concepto:descuentos[i].concepto, valor:0, tipo:descuentos[i].tipo, porcentaje:descuentos[i].porcentaje})
                  };
              for (var i = 0; i < deducciones.length; i++) {
                    me.agregarDeduccion({id:deducciones[i].id, concepto:deducciones[i].concepto, valor:0, tipo:deducciones[i].tipo, porcentaje:deducciones[i].porcentaje})
                  };
              
            }).catch(function(error){
              console.log(error);
            });
		},
        validarDatosSalario(){
			let checkStatus = document.getElementById('confirm_sal');
			if (checkStatus.checked) {
				let data = [this.id_beneficiosAgregados, this.id_deduccionesAgregados.concat(this.id_descuentosAgregados)]
				if(data[0].length==0 || data[1].length==0){
					this.avisar(true, false);
					checkStatus.checked=false;
				}else{
					this.avisar(true, data);
				}
			}else{
				this.avisar(false, false); 
			};
		},
		agregarBeneficio(dato){
			if (dato.concepto == "Prima de Profesionalización") {
				this.primaProfesional(dato);
			}else if(dato.concepto == "Prima de Antiguedad"){
				this.primaAntiguedad(dato);
			}else{
				if(dato.tipo_valor == 'U.T') {
                    dato.valor = (dato.valor*this.UT).toFixed(2);
                }else if(dato.tipo_valor == '%'){
                    dato.valor= ((dato.valor*this.salarioTabla)/100).toFixed(2);
                };

                this.beneficiosEmpleado.push(dato);
			 	this.id_beneficiosAgregados.push(dato.id);
			 	this.listarBeneficios();
			 	this.calcularTotalAsig();
			}

			this.validarDatosSalario();
		},
		agregarDescuento(dato){
			dato.valor = ((this.salarioTabla*dato.porcentaje)/100).toFixed(2);
			this.descuentosEmpleado.push(dato);
			this.id_descuentosAgregados.push(dato.id);
			this.listarDescuentos();
			this.calcularTotalDesc();
		},
		agregarDeduccion(dato){
			if (dato.concepto =='S.S.O' || dato.concepto == 'R.P.E') {
				dato.valor = this.sso_rpe(dato)
			}else if(dato.concepto == 'V.H' || dato.concepto == 'F.J'){
				dato.valor = ((this.salarioNormal*dato.porcentaje)/100).toFixed(2);
			}else{
				dato.valor = ((this.salarioNormal*dato.porcentaje)/100).toFixed(2);
			}
			this.deduccionesEmpleado.push(dato);
			this.id_deduccionesAgregados.push(dato.id);
			this.listarDeducciones();
			this.calcularTotalDeduc();
		},
		retirarBeneficio(id){
			let beneficio_index = this.id_beneficiosAgregados.indexOf(id);
            this.id_beneficiosAgregados.splice(beneficio_index, 1);
            this.beneficiosEmpleado.splice(beneficio_index, 1);
            this.listarBeneficios();
            this.calcularTotalAsig();
		},
		retirarDescuento(id){
			let descuento_index = this.id_descuentosAgregados.indexOf(id);
            this.id_descuentosAgregados.splice(descuento_index, 1);
            this.descuentosEmpleado.splice(descuento_index, 1);
            this.listarDescuentos();
		},
		retirarDeduccion(id){
			let deduccion_index = this.id_deduccionesAgregados.indexOf(id);
            this.id_deduccionesAgregados.splice(deduccion_index, 1);
            this.deduccionesEmpleado.splice(deduccion_index, 1);
            this.listarDeducciones();
		},
		exist(id, busqueda){
			switch(busqueda){
				case 'bene':
					return (this.id_beneficiosAgregados.includes(id))? true : false;
				break;
				case 'deduc':
					return (this.id_deduccionesAgregados.includes(id))? true : false;
				break;
				case 'desc':
					return (this.id_descuentosAgregados.includes(id))? true : false;
				break;
			}
		},
		primaProfesional(dato){

			let url = (this.id == '')?'/empleados/primaProfesional/' + this.instruccion + '/' + this.salarioTabla : 'pagos/primaProfesional/' + this.id + '/' + this.salarioTabla;

			axios.get(url).then((response)=>{
				
				dato.valor = response.data.toFixed(2);
				this.beneficiosEmpleado.push(dato);
			    this.id_beneficiosAgregados.push(dato.id);
			    this.listarBeneficios();
			    this.calcularTotalAsig();

			}).catch((error)=>{
				console.log(error);
			})
		},
		primaAntiguedad(dato){
			let prima_valor = 0;

			prima_valor = ((this.salarioNormal*dato.valor)/100)*this.anos;
			dato.valor = prima_valor.toFixed(2);
			this.beneficiosEmpleado.push(dato);
			this.id_beneficiosAgregados.push(dato.id);
			this.calcularTotalAsig();		
		},
		calcularTotalAsig(){
			let total = parseFloat(this.salarioTabla);
			
			for (var i = 0; i < this.beneficiosEmpleado.length; i++) {
				total += parseFloat(this.beneficiosEmpleado[i].valor);
			};

			this.salarioNormal = total;
			this.totalAsig = this.salarioNormal - (this.salarioTabla/2);
		},
		calcularTotalDesc(){
			let total = 0;
			
			for (var i = 0; i < this.descuentosEmpleado.length; i++) {

				total += parseFloat(this.descuentosEmpleado[i].valor);
			};

			this.totalDesc = total;
		},
		calcularTotalDeduc(){
			let total = 0;
			
			for (var i = 0; i < this.deduccionesEmpleado.length; i++) {
				total += parseFloat(this.deduccionesEmpleado[i].valor);
			};

			this.totalDeduc = total;
		},
		sso_rpe(dato){
			let valor = ((((this.salarioNormal) * 12)/52)*dato.porcentaje/100)*4;
			return valor.toFixed(2);
		}
	},
	mounted() {
		this.datoSalario();
    } 
};	
</script>