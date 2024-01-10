<x-app-layout>
    <x-slot name="page_header">
        <link href="assets/css/userflow.css" rel="stylesheet">
    </x-slot>
    
	<div class="container">
		<div class="row p-3 g-3"> 
			
		<form class="row g-3">
		 	<div class="col-md-3">
				<label for="sex" class="form-label col-form-label-sm">Sexo</label>
				<select id="sex" class="form-select form-control form-control-sm" name="sex" >
					<option value=0 selected>Selecciona...</option>
					<option value=1>Hombre</option>
					<option value=2>Mujer</option>
					<option value=3>Otro</option>
				</select>
		  	</div>            
		  	<div class="col-md-3">
				<label for="name" class="form-label col-form-label-sm">Nombre *</label>
				<input type="text" class="form-control form-control-sm" id="name" name="name"> 
		  	</div>
		  	<div class="col-md-3">
				<label for="lastname_1" class="form-label col-form-label-sm">Primer apellido *</label>
				<input type="password" class="form-control form-control-sm" id="lastname_1" name=" lastname_1">
		  	</div>
		  	<div class="col-md-3">
				<label for="lastname_2" class="form-label col-form-label-sm">Segundo apellido</label>
				<input type="password" class="form-control form-control-sm" id="lastname_2" name=" lastname_2">
		  	</div>		  	
		 	<div class="col-md-2">
				<label for="document_type" class="form-label col-form-label-sm">Documento *</label>
				<select id="document_type" class="form-select form-control form-control-sm" name= "document_type">
					<option value=0 selected>Selecciona...</option>
					<option value=1>DNI</option>
					<option value=2>NIE</option>
					<option value=3>Documento UE</option>
					<option value=4>Otro</option>
				</select>
		  	</div>            
		  	<div class="col-md-2">
				<label for="document_number" class="form-label col-form-label-sm">Número documento *</label>
				<input type="text" class="form-control form-control-sm" id="document_number" name="document_number">
		  	</div>
		  	<div class="col-md-2">
				<label for="dob" class="form-label col-form-label-sm">Fecha nacimiento</label>
				<input type="text" class="form-control form-control-sm" id="dob" name="dob">
		  	</div>				  	
		  	<div class="col-md-3">
				<label for="phone" class="form-label col-form-label-sm">Teléfono *</label>
				<input type="text" class="form-control form-control-sm" id="phone" name="phone">
		  	</div>		  	
		  	<div class="col-md-3">
				<label for="email" class="form-label col-form-label-sm">Email *</label>
				<input type="email" class="form-control form-control-sm" id="email" name="email">
		  	</div>
		  	<div class="col-md-3">
				<label for="town" class="form-label col-form-label-sm">Localidad de residencia</label>
				<input type="text" class="form-control form-control-sm" id="town" placeholder="" name="town">
		  	</div>
		  	<div class="col-md-3">
				<label for="province" class="form-label col-form-label-sm">Provincia</label>
				<input type="text" class="form-control form-control-sm" id="province" placeholder="" name="province">
		  	</div>
		  	<div class="col-md-3">
				<label for="community" class="form-label col-form-label-sm">Comunidad</label>
				<input type="text" class="form-control form-control-sm" id="community" placeholder="" name="community">
		  	</div>
		  	<div class="col-md-3">
				<label for="country" class="form-label col-form-label-sm">País</label>
				<input type="text" class="form-control form-control-sm" id="country" placeholder="" name="country">
		  	</div>		 		  		
		 	<div class="col-md-4">
				<label for="document_type" class="form-label col-form-label-sm">¿Cómo nos conociste?</label>
				<select id="document_type" class="form-select form-control form-control-sm" name= "document_type">
					<option value=0 selected>Selecciona...</option>
					<option value=1>Por algún conocido</option>
					<option value=2>Buscador en internet</option>
					<option value=3>Prensa / TV / Radio</option>
					<option value=4>Redes sociales</option>
					<option value=5>Otro</option>
				</select>
		  	</div>       
		  	<div class="col-md-4">
				<label for="inputCity" class="form-label col-form-label-sm">¿De qué comunidades deseas recibir información?</label>
				<input type="text" class="form-control form-control-sm" id="inputCity" name="aa">
		  	</div>
		  	<div class="col-md-4">
				<label for="inputCity" class="form-label col-form-label-sm">¿Dónde te gistaría vivir?</label>
				<input type="text" class="form-control form-control-sm" id="inputCity" name="aa">
		  	</div>

		  	<div class="col-md-6 px-0">
			  	<div class="col-md-12">
			  		<label></label>
			  		<div class="border-bottom">
			  			¿Deseas recibir información? 
			  		</div>
				</div>
				<div class="row px-4">
				 	<div class="col-md-6">
						<label for="document_type" class="form-label col-form-label-sm">Sobre:</label>
						<select id="document_type" class="form-select form-control form-control-sm" name= "document_type">
							<option value=0 selected>Selecciona...</option>
							<option value=1>Viviendas</option>
							<option value=2>Negocios</option>
							<option value=3>Tierras</option>
							<option value=4>Ofertas de trabajo</option>
							<option value=5>Todo</option>
						</select>
				  	</div>       
				 	<div class="col-md-6">
						<label for="document_type" class="form-label col-form-label-sm">En régimen de:</label>
						<select id="document_type" class="form-select form-control form-control-sm" name= "document_type">
							<option value=0 selected>Selecciona...</option>
							<option value=1>Venta</option>
							<option value=2>Alquiler</option>
							<option value=3>Otras opciones</option>
							<option value=4>Todo</option>
						</select>
				  	</div>
				</div>		  	  	
		  	</div>       

		  	<div class="col-md-6 px-0">
			  	<div class="col-md-12">
			  		<label></label>
			  		<div class="border-bottom">
			  			¿Cuántas personas se irían a vivir contigo? 
			  		</div>
			  	</div>
			  	<div class="row px-4">
				  	<div class="col-md-6">
						<label for="town" class="form-label col-form-label-sm">Adultos (incluyéndote)</label>
						<input type="text" class="form-control form-control-sm" id="town" placeholder="" name="town">
				  	</div>
				  	<div class="col-md-6">
						<label for="province" class="form-label col-form-label-sm">Niños</label>
						<input type="text" class="form-control form-control-sm" id="province" placeholder="" name="province">
				  	</div>
			  	</div>
		  	</div>

		  	<div class="col-md-6 px-0 pb-3">
			  	<div class="col-md-12">
			  		<label></label>
			  		<div class="border-bottom">
			  			¿Tienes algún proyecto de emprendimiento? 
			  		</div>
				</div>
			  	<div class="row px-4">
				  	<div class="col-md-12">
						<input type="text" class="form-control form-control-sm" id="town" placeholder="" name="town">
						<small>(Por ejemplo: bar, casa turismo rural, panadería...)</small>
				  	</div>
			  	</div>  	  	
		  	</div>       

		  	<div class="col-md-6 px-0 pb-3">
			  	<div class="col-md-12">
			  		<label></label>
			  		<div class="border-bottom">
			  			¿Tienes experiancia en...? 
			  		</div>
			  	</div>
			  	<div class="row px-4">
				  	<div class="col-md-12">
						<input type="text" class="form-control form-control-sm" id="town" placeholder="" name="town">
						<small>(Por ejemplo: cocinero/a, albañil, agricultor/a)</small>
				  	</div>
			  	</div>
		  	</div>
		  	<div class="col-md-12 pb-3">
				<label for="inputCity" class="form-label col-form-label-sm">Cuéntanos sobre tu proyecto de vida!</label>
				<textarea type="text" class="form-control form-control-sm" id="inputCity" name="aa"></textarea>
				<small>Ayúdanos a comprender, para asistirte mejor: ¿Qué tipo de vivienda buscas? (con terreno; lista para vivir o para reformar; rango de precios que te interesa) Si es un negocio, ¿tipología, necesidades?. En el caso de las tierras, ¿qué características debe tener? ¿qué uso quieres darle? ¿En caso de trabajo, ¿qué tipo de trabajo buscas?.</small>
		  	</div>

		  	<div class="col-md-6 pb-3">
				<label for="inputCity" class="form-label col-form-label-sm">¿Deseas realizar un itinerario de inserción sociolaboral?</label>
				<input type="text" class="form-control form-control-sm" id="inputCity" name="aa">
		  	</div>
		 	<div class="col-md-6 pb-3">
				<label for="document_type" class="form-label col-form-label-sm">¿Qué tipo de itinerartio?</label>
				<select id="document_type" class="form-select form-control form-control-sm" name= "document_type">
					<option value=0 selected>Selecciona...</option>
				<option value=1>Agricultura ecológica </option>
				<option value=2>Apicultura </option>
				<option value=3>Emprendimiento y autoempleo sostenible </option>
				<option value=4>Comercialización de productos de proximidad y/o ecológicos </option>
				<option value=5>Procesado y elaboración de alimentos de proximidad y/o ecológicos </option>
				<option value=6>Ganadería ecológica </option>
				<option value=7>Transformación de alimentos de proximidad y/o ecológicos </option>
				<option value=8>Restauración y turismo </option>
				<option value=9>Atención a personas mayores y/o dependientes </option>
				<option value=10>Otros </option>
				</select>
		  	</div> 	

		  	<div class="col-md-12 pb-3">
				<label for="inputCity" class="form-label col-form-label-sm">¿Deseas comentarnos algo más? Hazlo aquí!</label>
				<textarea type="text" class="form-control form-control-sm" id="inputCity" name="aa"></textarea>
				<small>Ayúdanos a comprender, para asistirte mejor: ¿Qué tipo de vivienda buscas? (con terreno; lista para vivir o para reformar; rango de precios que te interesa) Si es un negocio, ¿tipología, necesidades?. En el caso de las tierras, ¿qué características debe tener? ¿qué uso quieres darle? ¿En caso de trabajo, ¿qué tipo de trabajo buscas?.</small>
		  	</div>
		</form>
	</div>
</x-app-layout>
