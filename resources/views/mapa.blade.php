<x-app-layout>
    <x-slot name="page_header">
		<!-- Map related stuff starts here-->
		<link href="/assets/css/map.css" rel="stylesheet">
		<!-- Map related stuff ends here-->
    </x-slot>

	<div id="map" style="height: calc(100vh - 65px)"></div>

        <div id="main-container" class="main-container container-fluid d-none">

            <div id="filters" class="filters floating">

                <div class="btn-group dropright communities mb-2">
                    <button id="btn-communities" type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Comunidades
                    </button>
                    <div class="dropdown-menu">
                		<a class="community-item dropdown-item active"
							href="#" 
							data-id="0"
							data-lat="39.86338991167967"
							data-lng="-4.027926176082693"
							data-zoom="6"                    		
                		>Todas
                		</a>
						@foreach ($communities as $c)
							<a class="community-item dropdown-item" 
								href="#" 
								data-id="{{ $c->id}}"
								data-lat="{{ $c->map_center_lat}}"
								data-lng="{{ $c->map_center_lng}}"
								data-zoom="{{ $c->map_zoom}}"
							>{{ $c->name }}
							</a>    
						@endforeach
                    </div>
                </div>

                <div class="btn-group dropright resources mb-2">
                    <button id="btn-resources" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Recursos
                    </button>
                    <div class="dropdown-menu">
                    	<a class="resource-item dropdown-item active" data-id="0" data-res="Todo" href="#">Todos</a>
                    	<a class="resource-item dropdown-item Viviendas" data-id="1" data-res="House" href="#">Viviendas</a>
                        <a class="resource-item dropdown-item Negocios" data-id="2" data-res="Business" href="#">Negocios</a>
                        <a class="resource-item dropdown-item Tierras" data-id="3" data-res="Land" href="#">Tierras</a>
                        <a class="resource-item dropdown-item Trabajos" data-id="4" data-res="Job" href="#">Trabajos</a>              	
                    </div>
                </div>

                <div class="btn-group dropright sector mb-2 d-none">
                    <button id="btn-sectors" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Sector
                    </button>
                    <div class="dropdown-menu">
                        <a class="sector-item dropdown-item active" data-id="0" href="#">Todos</a>
						@foreach ($sectors as $s)
							<a class="sector-item dropdown-item" 
								href="#" 
								data-id="{{ $s->id}}"
							>{{ $s->name }}
							</a>    
						@endforeach                             
                    </div>
                </div>

                <div class="btn-group dropright form">
                    <button id="btn-forms" type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Régimen
                    </button>
                    <div class="dropdown-menu">
                        <a class="form-item dropdown-item active" data-id="0" href="#">Todos</a>
						@foreach ($forms as $f)
							<a class="form-item dropdown-item" 
								href="#" 
								data-id="{{ $f->id}}"
							>{{ $f->name }}
							</a>    
						@endforeach                             
                    </div>
                </div>                                

                <div class="btn-group dropright formjob d-none">
                    <button id="btn-jobforms" type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Régimen
                    </button>
                    <div class="dropdown-menu">
                        <a class="jobform-item dropdown-item active" data-id="0" href="#">Todos</a>
						@foreach ($jobForms as $j)
							<a class="jobform-item dropdown-item" 
								href="#" 
								data-id="{{ $j->id}}"
							>{{ $j->name }}
							</a>    
						@endforeach                             
                    </div>
                </div>            
            </div>

			<!-- CDR Modal -->
			<div class="modal" id="datos-cdr" tabindex="-1" aria-labelledby="datos-cdr" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body p-5">
							<div class="row">
								<div class="col-md-8">
									<span class="nombre"></span>
									<span class="direccion"></span>
									<span class="ciudad"></span>
									<span class="horario"></span>
									<span class="telefono"></span>
									<a class="email-link"></a>
									<a class="web" target="_blank"></a>
									<br>
									<a class="link" target="_blank"></a>
								</div>
								<div class="col-sm-4">
									<img class="logo img-fluid"></img>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

			<div id="photo" class="d-none">
            	<img class="photo-big">	
            </div>  
        </div>

        <div id="data-container" class="contenedor" class="d-none">
	    			
        	<div id="data-item" class="data-item d-none">

        		<div class="wrapper">
	    			<span class="close icon-close icon-data-item">
	    				<i class="fa-solid fa-xmark"></i>
	    			</span>


	    			<div id="resource" class="container inner-resource">

	    				<!-- Title section -->
	    				<div class="row resource-title mb-2">
	    					<div class="col-1 text-center">
	    						<i class="resource fa-solid" data-bs-toggle="tooltip" title="Tipo de recurso"></i>
	    					</div>
	    					<div class="col">
	    						<span class='resource-type'></span>
	    					</div>
	    					<div class="col-1 text-center" data-bs-toggle="tooltip" title="Referencia del recurso.">
	    						<i class="fa-solid fa-tag"></i>
	    					</div>

	    					<div class="col">
	    						<span class="reference"></span>
	    					</div>	
	    				</div>				

	    				<!-- Resoure specific for HOUSE -->
	    				<div class="specific-details house">
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-people-roof" data-bs-toggle="tooltip" title="Estado de habitabilidad"></i>
	        					</div>
	        					<div class="col">
	        						<span class='status'></span>
	        					</div>
	        				</div>            					
	        				<div class="row mb-2">
	        					<div class="col-1 text-center"></div>
	        					<div class="col">
	        						<i class="fa-solid fa-stairs" data-bs-toggle="tooltip" title="Plantas"></i> <span class='stories'></span>
	        					</div>
	        					<div class="col">
	        						<i class="fa-solid fa-bed" data-bs-toggle="tooltip" title="Dormitorios"></i> <span class='bedrooms'></span>
	        					</div>            					
								<div class="col">
	        						<i class="fa-solid fa-toilet" data-bs-toggle="tooltip" title="Baños"></i> <span class='bathrooms'></span>
	        					</div>
								<div class="col">
	        						<i class="fa-solid fa-door-open" data-bs-toggle="tooltip" title="Total estancias"></i> <span class='rooms'></span>
	        					</div>
	        				</div>
	  						<div class="row mb-2">
	        					<div class="col-1 text-center"></div>
	        					<div class="col">
	        						<i class="extra-courtyard fa-solid fa-seedling position-relative" data-bs-toggle="tooltip" title="Patio">
	        							<i class="fa-solid fa-ban nested-ban d-none"></i>
	        						</i>
	        					</div>
	        					<div class="col">
	        						<i class="extra-stables fa-solid fa-cow position-relative" data-bs-toggle="tooltip" title="Edificaciones auxiliares">
	        							<i class="fa-solid fa-ban nested-ban d-none"></i>
	        						</i>
	        					</div>            					
								<div class="col">
	        						<i class="extra-lands fa-solid fa-wheat-awn position-relative" data-bs-toggle="tooltip" title="Tierras">
	        							<i class="fa-solid fa-ban nested-ban d-none"></i>
	        						</i>
	        					</div>
								<div class="col">
	        						<i class="extra-tobusiness fa-solid fa-store position-relative" data-bs-toggle="tooltip" title="Adaptable para negocio">
	        							<i class="fa-solid fa-ban nested-ban d-none"></i>
	        						</i>
	        					</div>
	        				</div>
	    				</div>  

	    				<!-- Resoure specific for BUSINESS -->
	    				<div class="specific-details business">
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-chart-line" data-bs-toggle="tooltip" title="Sector"></i>
	        					</div>
	        					<div class="col">
	        						<span class='sector'></span>
	        					</div>
	        				</div>
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-store" data-bs-toggle="tooltip" title="Tipo de negocio"></i>
	        					</div>
	        					<div class="col">
	        						<span class='property-type'></span>
	        					</div>
	        				</div>
	        				<div class="row detail mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-list-check" data-bs-toggle="tooltip" title="Requisitos"></i>
	        					</div>
	        					<div class="col">
	        						<span class='terms'></span>
	        					</div>
	        				</div>
	        				<div class="row detail mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-clock" data-bs-toggle="tooltip" title="Plazos"></i>
	        					</div>
	        					<div class="col">
	        						<span class='deadlines'></span>
	        					</div>
	        				</div>	            				
	    				</div>


	    				<!-- Resoure specific for JOBS -->
	    				<div class="specific-details job">
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-chart-line" data-bs-toggle="tooltip" title="Sector"></i>
	        					</div>
	        					<div class="col">
	        						<span class='sector'></span>
	        					</div>
	        				</div>
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-person-circle-check" data-bs-toggle="tooltip" title="Puesto de trabajo"></i>
	        					</div>
	        					<div class="col">
	        						<span class='position'></span>
	        					</div>
	        				</div>
	        				<div class="row detail mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-list-check" data-bs-toggle="tooltip" title="Requerimientos"></i>
	        					</div>
	        					<div class="col">
	        						<span class='requirements'></span>
	        					</div>
	        				</div>
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-chart-line" data-bs-toggle="tooltip" title="Sector"></i>
	        					</div>
	        					<div class="col">
	        						<span class='sector'></span>
	        					</div>
	        				</div>	            				      				
	    				</div>

	    				<!-- Resoure specific for LANDS -->
	    				<div class="specific-details land">
	    					<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-arrows-left-right" data-bs-toggle="tooltip" title="Área"></i>
	        					</div>
	        					<div class="col">
	        						<span class='arearange'></span>
	        					</div>
	        				</div>	    
	    					<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-grip" data-bs-toggle="tooltip" title="Tipo de tierra"></i>
	        					</div>
	        					<div class="col">
	        						<span class='landtype'></span>
	        					</div>
	        				</div>	    
	    					<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-hand" data-bs-toggle="tooltip" title="Uso"></i>
	        					</div>
	        					<div class="col">
	        						<span class='landuse'></span>
	        					</div>
	        				</div>	    	            					            				
	        			</div>	  

	    				<!-- Titularidad y régimen -->
	    				<div class="ownership-regime">
	        				<div class="row mb-2">
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-file-contract" data-bs-toggle="tooltip" title="Titularidad"></i>
	        					</div>
	        					<div class="col">
	        						<span class='ownership'></span>
	        					</div>
	        					<div class="col-1 text-center">
	        						<i class="fa-solid fa-file-signature" data-bs-toggle="tooltip" title="Régimen"></i>
	        					</div>
	        					<div class="col">
	        						<span class='regime'></span>
	        					</div>
	        				</div>
	    				</div>

	    				<!-- Rango de precios -->
	    				<div class="row mb-2">
	    					<div class="col-1 text-center">
	    						<i class="fa-solid fa-euro-sign" data-bs-toggle="tooltip" title="Rango de precios"></i>
	    					</div>
	    					<div class="col">
	    						<span class='price'></span>
	    					</div>
	    				</div>     

	    				<!-- Description -->
	    				<div class="row detail mb-2">
	    					<div class="col-1 text-center">
	    						<i class="fa-solid fa-circle-info" data-bs-toggle="tooltip" title="Descripción del recurso"></i>
	    					</div>
	    					<div class="col">
	    						<span class="description"></span>
	    					</div>
	    				</div>            

	    				<!-- CDR section -->
		            	<div class="row cdr mb-2">
		            		<div class="col-1 text-center">
								<i class="fa-solid fa-building-columns" data-bs-toggle="tooltip" title="CDR o centro responsable"></i>
		            		</div>
		            		<div class="col">
		            			<span class="cdr-name">
		            				<!-- 
		            				<a href="#" data-bs-toggle="tooltip" title="Ver información"></a> <i class="fa-solid fa-arrow-up-right-from-square"></i> 
		            				-->
		            				<a id="ver-cdr" href="#datos-cdr" data-toggle="modal" data-target="#datos-cdr"></a> <i class="fa-solid fa-arrow-up-right-from-square"></i> 
		            			</span>
		            		</div>
		            	</div>

		            	<!-- Location section -->
		            	<div class="row location mb-2">
		            		<div class="col-1 text-center">
								<i class="fa-solid fa-location-dot" data-bs-toggle="tooltip" title="Localización"></i>
		            		</div>
		            		<div class="col">
			        			<span class="community" data-bs-toggle="tooltip" title="Cominudad Autónoma"></span>
			        			<span class="province" data-bs-toggle="tooltip" title="Provincia"></span>
			        			<span class="municipality" data-bs-toggle="tooltip" title="Municipio"></span>
			        			<span class="town" data-bs-toggle="tooltip" title="Localidad (Habitantes)"></span>			            			
		            		</div>
		            	</div>

		            	<!-- Fotos section -->
		            	<div class="row photos mb-2 d-none">
		            		<div class="col-1 text-center">
								<i class="fa-solid fa-camera" data-bs-toggle="tooltip" title="Localización"></i>
		            		</div>
		            		<div class="col">
								<div class="gallery"></div>		            			
		            		</div>
		            	</div>
		            </div>
	            </div>

        	</div>
        	
        	<div id="data-list" class="data-list d-none">
        		<div class="wrapper">
	    			<span class="close icon-close icon-data-list">
	    				<i class="fa-solid fa-xmark"></i>
	    			</span>

	    			<div id="resource-list" class="container inner-resource-list">
	    				<span class="list-title">
	    					<strong>
	    						<span class="grouped-resources"></span> 
	    						Recursos agrupados en:
	    					</strong>
	    				<br />
	    				</span>	    				
	    				<span class="list-location"></span>
	    				<hr>
	    				<ul class="list-group"></ul>
	    			</div>	
    			</div>
    		</div>
	    </div>	

    <x-slot name="page_scripts">
    	<!-- Map related stuff -->
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6jHWMPyq9CDtJ2ldR_mOaAtHfdvUPAJw&callback=initMap"></script>
        <script src="/assets/js/markerclusterer.js"></script>
        <script src="/assets/js/map.js"></script>

        <script>
        	var map;
        	var communities = {!! $communities !!};
        	var sectors = {!! $sectors !!}
            var markers = @json($markers);
            var marcadores = [];
            var clusters;

            var ms = [];
        </script>
        <!-- Map related stuff ends here-->

    </x-slot>    
</x-app-layout>
