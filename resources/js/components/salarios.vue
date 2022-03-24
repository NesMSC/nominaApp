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
	                    	<strong><td colspan="2" v-text="formatoDivisa(totalBeneficios)"></td></strong>
	                  	</tr> 
	                  	<tr>
	                    	<td colspan="6" align="center">
	                      		<button @click="listarBeneficios()" class="btn btn-light" data-toggle="modal" data-target="#ModalBeneficios"><i class="fa fa-plus"></i>&nbsp;Agregar/Quitar</button>
	                    	</td>
	                  	</tr>
                  	</tbody>                  
                  	<tbody>
	                    <tr style="background-color: #343a40">
	                      <td colspan="6" align="center" class="text-light">RETENCIONES</td>
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
	                      <td colspan="6" align="center" class="text-light">APORTES</td>
	                    </tr>
 	                    <tr v-for="aporte in aportesEmpleado" :key="aporte.id">
	                      <td colspan="4" v-text="aporte.concepto"></td>
	                      <td colspan="2" v-text="formatoDivisa(aporte.valor)"></td>
	                    </tr>
	                    <tr v-if="aportesEmpleado.length != 0">
	                      <td colspan="4"><strong>Total de Aportes:</strong></td>
	                      <strong><td colspan="2" v-text="formatoDivisa(totalAportes)"></td></strong>
	                    </tr>
	                    <tr >
	                      <td colspan="6" align="center">
	                        <button @click="listarDeducciones()" class="btn btn-light" data-toggle="modal" data-target="#ModalAportes"><i class="fa fa-plus"></i>&nbsp;Agregar/Quitar</button>
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
						<tr style="background-color: #CEEFCF5">
	                      <td colspan="4"><strong>Total Mensual:</strong></td>
	                      <td colspan="2" v-text="formatoDivisa(parseFloat(totalAsig))"></td>
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
								<td v-if="beneficio.tipo_valor == 'especifico'">{{beneficio.valor}}</td>
								<td v-else v-text="beneficio.valor+' '+ beneficio.tipo_valor"></td>
								<td>
									<template v-if="exist(beneficio.id, beneficiosEmpleado)">
									<a href="#" @click.prevent="retirarBeneficio(beneficio.id)">
										<i class="fa fa-check text-success"></i>
									</a>
									</template>
									<template v-else>
										<span 
										v-if="(personal == 'Obrero') && (beneficio.concepto == 'Prima de Profesionalización' || beneficio.concepto == 'Prima Apoyo a la actividad Docente')" 
										class="text-danger">
											No disponible
										</span>
										<span v-else-if="(anos == 0) && (beneficio.concepto == 'Prima de Antiguedad')" class="text-danger">
											No disponible
										</span>
										<a v-else href="#" 
										@click.prevent="agregarBeneficio({
															id:beneficio.id,concepto:beneficio.concepto, 
															tipo_valor:beneficio.tipo_valor, valor:beneficio.valor, 
															tipo_valor_por:beneficio.tipo_valor_por, 
															incidencia:beneficio.incidencia
														})">
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
	                            <template v-if="exist(deduccion.id, deduccionesEmpleado)">
	                              <a href="#" @click.prevent="retirarDeduccion(deduccionesEmpleado, deduccion.id)">
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
		<!-- Modal Aportes-->
		<div class="modal fade" id="ModalAportes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	                        <tr v-for="aporte in arrayAportes" :key="aporte.id">
	                          <td v-text="aporte.concepto"></td>
	                          <td v-text="aporte.tipo"></td>
	                          <td v-text="aporte.porcentaje+'%'"></td>
	                          <td>
	                            <template v-if="exist(aporte.id, aportesEmpleado)">
	                              <a href="#" @click.prevent="retirarDeduccion(aportesEmpleado, aporte.id)">
	                                <i class="fa fa-check text-success"></i>
	                              </a>
	                            </template>
	                            <template v-else>
	                              <a href="#" @click.prevent="agregarDeduccion({id:aporte.id, concepto:aporte.concepto, valor:0, tipo:aporte.tipo, porcentaje:aporte.porcentaje})">
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
	                            <template v-if="exist(descuento.id, descuentosEmpleado)">
	                              <a href="#" @click.prevent="retirarDeduccion(descuentosEmpleado, descuento.id)">
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
		instruccion: "",
		hijos: Array,
	},
	data(){
		return {
            arrayBeneficios: [],
            arrayDescuentos: [],
            arrayDeducciones: [],
			arrayAportes: [],
            salarioTabla: 0,
			salarioMinimoMensual: 0,
            beneficiosEmpleado: [],
            descuentosEmpleado: [],
            deduccionesEmpleado: [],
			aportesEmpleado: [],
            id_beneficiosAgregados: [],
            id_deduccionesAgregados: [],
            id_descuentosAgregados: [],
            totalAsig: 0,
            salarioNormal: 0,
			totalBeneficios: 0,
			totalExtra: 0,
            totalDesc: 0,
            totalDeduc: 0,
			totalAportes: 0,
            UT: 0,
            primAntiguedadPorcentaje: Number,
			primAntiguedadId: Number,
            primAntiguedadIndice: -1,
			primaProfesionalValue:0,
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
              me.arrayDeducciones = response.data.deducciones.filter(e => e.tipo == 'Retención');
			  me.arrayAportes = response.data.deducciones.filter(e => e.tipo == 'Aporte');
            }).catch(function(error){
              console.log(error);
            })
        },
        formatoDivisa(number){
           let monto = new Intl.NumberFormat('de-DE').format(number);
           return monto + ' Bs';
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
				  me.salarioMinimoMensual = response.data.salarioMin + response.data.cestaTicket;
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

              		me.agregarBeneficio({
						  id:beneficios[i].id,
						  concepto:beneficios[i].concepto, 
						  tipo_valor:beneficios[i].tipo_valor, 
						  valor:beneficios[i].valor, 
						  tipo_valor_por:beneficios[i].tipo_valor_por,
						  incidencia: beneficios[i].incidencia
						})
                };

              	for (var i = 0; i < descuentos.length; i++) {
                    me.agregarDescuento({
						id:descuentos[i].id,
						concepto:descuentos[i].concepto, valor:0, 
						tipo:descuentos[i].tipo, 
						porcentaje:descuentos[i].porcentaje})
                };

             	for (var i = 0; i < deducciones.length; i++) {
                    me.agregarDeduccion({
						id:deducciones[i].id, 
						concepto:deducciones[i].concepto, 
						valor:0, tipo:deducciones[i].tipo, 
						porcentaje:deducciones[i].porcentaje})

						console.log(`for ej ${deducciones[i].tipo}`)
                };
              

            }).catch(function(error){
              console.log(error);
            });
		},
        validarDatosSalario(){
			let checkStatus = document.getElementById('confirm_sal');
			if (checkStatus.checked) {
				const aportesId = this.aportesEmpleado.map(e => e.id);
				let data = [this.id_beneficiosAgregados, this.id_deduccionesAgregados.concat([...this.id_descuentosAgregados, ...aportesId])]
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
			}else if(dato.concepto  == "Prima por hijos e hijas"){
					if(!this.hijos.length){
						Vue.toasted.error( 'Sin hijos registrados no aplica', 
						{duration:2000, className:['alert', 'alert-danger']});
					}else{
						let validos = this.primaHijos();

						dato.valor = (((dato.valor*this.salarioMinimoMensual)/100)*validos).toFixed(2);
						dato.concepto += ` (${validos})`;
						this.beneficiosEmpleado.push(dato);
			 			this.id_beneficiosAgregados.push(dato.id);
						this.listarBeneficios();
			 			this.calcularTotalAsig();
					}
			}else{

				if(dato.tipo_valor == 'U.T') {
                    dato.valor = (dato.valor*this.UT).toFixed(2);
                }else if(dato.tipo_valor == '%'){
					if(dato.tipo_valor_por == 'salario_tabla'){
						dato.valor = ((dato.valor*this.salarioTabla)/100).toFixed(2);
					}else if(dato.tipo_valor_por == 'salario_min_mensual'){
						dato.valor = ((dato.valor*this.salarioMinimoMensual)/100).toFixed(2);
					}else{
						dato.valor = ((dato.tipo_valor_por*dato.valor)/100).toFixed(2);
					};
                    
                };
				
                this.beneficiosEmpleado.push(dato);
			 	this.id_beneficiosAgregados.push(dato.id);
				this.calcularFormula();
			 	this.listarBeneficios();
			 	this.calcularTotalAsig();
			}

			

			if (this.primAntiguedadIndice != -1) {
			 		this.cambiarPrimaAntiguedad();
			}

			this.calcularTotalBeneficios();
			this.validarDatosSalario();
		},
		calcularTotalBeneficios(){
			this.totalBeneficios = this.beneficiosEmpleado.reduce((previous, current) => {
				return previous + parseFloat(current.valor);
			}, 0)
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
			}else if(dato.concepto == 'C.A'){
				dato.valor = ((this.salarioTabla*dato.porcentaje)/100).toFixed(2);
			}else{
				dato.valor = ((this.salarioNormal*dato.porcentaje)/100).toFixed(2);
			}
			
			console.log(dato.tipo)
			if(dato.tipo == 'Retención'){
				this.deduccionesEmpleado.push(dato);
				this.id_deduccionesAgregados.push(dato.id);
			}
			if(dato.tipo == 'Aporte'){
				this.aportesEmpleado.push(dato);
				console.log(`SE AGREGÓ ${dato.concepto}`)
			}
			this.listarDeducciones();
			this.calcularTotalDeduc();
		},
		retirarBeneficio(id){
			let beneficio_index = this.id_beneficiosAgregados.indexOf(id);
			if (this.beneficiosEmpleado[beneficio_index].concepto == 'Prima de Antiguedad') {
				this.primAntiguedadIndice = -1;
			}
            this.id_beneficiosAgregados.splice(beneficio_index, 1);
            this.beneficiosEmpleado.splice(beneficio_index, 1);
            this.listarBeneficios();
            this.calcularTotalAsig();
			if (this.primAntiguedadIndice != -1) {
					const ide = this.primAntiguedadId;
					this.primAntiguedadIndice = this.id_beneficiosAgregados.indexOf(ide);
			 		this.cambiarPrimaAntiguedad();
			};
		},
		retirarDeduccion(arr, id){
			arr.forEach((element, index) => {
				if(element.id == id){
					arr.splice(index, 1);
					this.listarDeducciones();
					this.calcularTotalDeduc();
					this.calcularTotalDesc();
				}
			});
		},
		exist(id, data){
			return data.some(e => e.id == id);
		},
		primaProfesional(dato){

			let url = (this.id == '')?'/empleados/primaProfesional/' + this.instruccion + '/' + this.salarioTabla : 'pagos/primaProfesional/' + this.id + '/' + this.salarioTabla;

			axios.get(url).then((response)=>{
				
				dato.valor = response.data.toFixed(2);
				this.primaProfesionalValue = dato.valor;
				this.beneficiosEmpleado.push(dato);
			    this.id_beneficiosAgregados.push(dato.id);
				this.calcularFormula();
			    this.listarBeneficios();
			    this.calcularTotalAsig();
			    if (this.primAntiguedadIndice != -1) {
			 		this.cambiarPrimaAntiguedad();
			 	};

			}).catch((error)=>{
				console.log(error);
			})
		},
		primaAntiguedad(dato){
			if(dato.tipo_valor != 'formula'){
				let primaValor = ((this.salarioNormal*dato.valor)/100)*this.anos;
				this.primAntiguedadPorcentaje = dato.valor;
				this.primAntiguedadId = dato.id;
				dato.valor = primaValor.toFixed(2);
				this.beneficiosEmpleado.push(dato);
				this.id_beneficiosAgregados.push(dato.id);
				this.primAntiguedadIndice = this.id_beneficiosAgregados.indexOf(dato.id);
			}
			this.calcularTotalAsig();
		},
		cambiarPrimaAntiguedad(){
			const indice = this.primAntiguedadIndice;
			const porcentaje = this.primAntiguedadPorcentaje;
			const valorAnterior = this.beneficiosEmpleado[indice].valor;
			let valorActual = (((this.salarioNormal-valorAnterior)*porcentaje)/100)*this.anos;
			this.beneficiosEmpleado[indice].valor = valorActual.toFixed(2);
			this.calcularTotalAsig();
			this.calcularFormula();
			this.calcularTotalBeneficios();
			
		},
		calcularTotalAsig(){
			let total = parseFloat(this.salarioTabla);
			this.totalExtra = 0;
			for (var i = 0; i < this.beneficiosEmpleado.length; i++) {
				if(this.beneficiosEmpleado[i].incidencia){
					total += parseFloat(this.beneficiosEmpleado[i].valor);
				}else{
					this.totalExtra += parseFloat(this.beneficiosEmpleado[i].valor);
				}
				
			};

			this.salarioNormal = total;

			//Recalcular deducciones
	 		let deducciones = [...this.deduccionesEmpleado];
			this.deduccionesEmpleado = [];
			this.id_deduccionesAgregados = [];
			for(let deduccion of deducciones){
				this.agregarDeduccion(deduccion);
			}

			//Recalcular aportes
	 		let aportes = [...this.aportesEmpleado];
			this.aportesEmpleado = [];
			for(let aporte of aportes){
				this.agregarDeduccion(aporte);
			}
		},
		calcularTotalNeto(){
			this.totalAsig = (this.salarioNormal + this.totalExtra).toFixed(2) - (this.totalDeduc).toFixed(2) - (this.totalDesc).toFixed(2);
		},
		calcularTotalDesc(){
			let total = 0;
			
			for (var i = 0; i < this.descuentosEmpleado.length; i++) {

				total += parseFloat(this.descuentosEmpleado[i].valor);
			};

			this.totalDesc = total;
			this.calcularTotalAsig();
		},
		calcularTotalDeduc(){
			const totalRetenciones = this.deduccionesEmpleado
									.filter(e => e.tipo == 'Retención')
									.reduce((previous, current) =>
										previous + parseFloat(current.valor)
									, 0);

			const totalAportes = this.aportesEmpleado
									.filter(e => e.tipo == 'Aporte')
									.reduce((previous, current) =>
										previous + parseFloat(current.valor)
									, 0);

			this.totalDeduc = totalRetenciones;
			this.totalAportes = totalAportes;
			this.calcularTotalNeto();
			
		},
		sso_rpe(dato){

			let valor = ((((this.salarioNormal) * 12)/52)*dato.porcentaje/100)*this.contarLunes();
			return valor.toFixed(2);
		},
		primaHijos(){
			let validos = 0;

			const arrayHijos = this.hijos;

			for(var i = 0; i < arrayHijos.length; i++){

				let edad = this.calculaEdadHijos(arrayHijos[i].nacimiento);
				
				if(edad > 21){
					if(arrayHijos[i].nivel == 'Universidad' && edad < 25){
						//console.log(`${arrayHijos[i].nombre} si aplica`)
						validos++
					}else if(arrayHijos[i].discapacidad != 'Ninguna'){
						//console.log(`${arrayHijos[i].nombre} si aplica`)
						validos++
					}
				}else{
					//console.log(`${arrayHijos[i].nombre} si aplica`)
					validos++
				}
			}

			return validos;
		},
		calculaEdadHijos(fecha){
            //Calcula la edad del hijo del trabajador

            fecha = new Date(fecha);
            let actual = new Date();

            let año = fecha.getFullYear();
            let mes = fecha.getMonth(); 

            let edad = actual.getFullYear()-año;

            if (mes >= actual.getMonth()  && edad != 0) {
              edad--;
            };

            return edad;
        },
		contarLunes(){
			let fecha = new Date();
			let currentYear = fecha.getFullYear();
			let currentMonth = fecha.getMonth();

			//La variable cont corresponde al primer dia del mes
			let cont = 1;
			let lunes = 0;
			while (true) {
				
				let fecha2 = new Date(currentYear, currentMonth, cont);
				//Verificar si el mes actual coincide con el de fecha2
				if(fecha2.getMonth() == currentMonth){
					//Verifica si el dia de la semana de fecha2 coincide con lunes (1)
					//Si es true, acumula +1 a la variable contadora lunes
					//Imprime las fechas que corresponden a esos lunes del mes
					if(fecha2.getDay() == 1){ lunes++;}
					//Suma uno a la fecha de fecha2
					cont++;
				}else{
					//Si es false, retorna el numero de lunes que se acumularon
					return lunes;
				}
			}
		},
		calcularFormula(){

			const primaAntiguedad = (this.beneficiosEmpleado[this.primAntiguedadIndice])? 
				this.beneficiosEmpleado[this.primAntiguedadIndice].valor
				: 0;
			const primaProfesional = this.primaProfesionalValue ?? 0;
			const dato = this.beneficiosEmpleado.find(e => e.tipo_valor == 'formula');

			if(dato){
				dato.valor = ((parseFloat(this.salarioTabla) + 
							parseFloat(primaAntiguedad) +
							parseFloat(primaProfesional))*(80/100)).toFixed(2);
			}
			
		}
	},
	mounted() {
		this.datoSalario();
    } 
};	
</script>