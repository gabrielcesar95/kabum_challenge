<?php $v->layout("_admin"); ?>

<script src="<?= theme("/assets/js/handlebars.min.js", CONF_VIEW_ADMIN); ?>"></script>

<div class="container">
	<header>
		<h1 class="mt-4"><?= isset($customer) && $customer ? "Edição de cliente: $customer->name" : 'Novo Cliente'; ?></h1>
	</header>
	
	<section>
		<form action="<?= url("/admin/clientes/" . (isset($customer) && $customer ? "editar/{$customer->id}" : 'novo')); ?>" enctype="multipart/form-data" method="post">
			<?php if (isset($customer) && $customer) : ?>
				<input type="hidden" name="user_id" value="<?= $customer->id; ?>">
				<input type="hidden" name="_method" value="PUT">
			<?php endif; ?>
			<div class="form-row">
				<div class="form-group col-md-8">
					<label for="name">Nome Completo:</label>
					<input type="text" name="name" id="name" value="<?= isset($customer->name) ? $customer->name : ''; ?>" class="form-control" required />
				</div>
				<div class="form-group col-md-4">
					<label for="birth_date">Data de Nascimento:</label>
					<input type="text" name="birth_date" id="birth_date" value="<?= isset($customer->birth_date) ? date_fmt($customer->birth_date, 'd/m/Y') : ''; ?>" class="form-control mask-date" />
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="cpf">CPF:</label>
					<input type="text" name="cpf" id="cpf" value="<?= isset($customer->cpf) ? $customer->cpf : ''; ?>" class="form-control mask-cpf" />
				</div>
				<div class="form-group col-md-4">
					<label for="rg">RG:</label>
					<input type="text" name="rg" id="rg" value="<?= isset($customer->rg) ? $customer->rg : ''; ?>" class="form-control" maxlength="30" />
				</div>
				<div class="form-group col-md-4">
					<label for="phone">Telefone:</label>
					<input type="text" name="phone" id="phone" value="<?= isset($customer->phone) ? $customer->phone : ''; ?>" class="form-control mask-phone" />
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-12">
					<h3>Endereços</h3>
				</div>
				
				<div class="form-group col-md-2">
					<label for="zip_code">CEP:</label>
					<input type="text" id="zip_code" class="form-control mask-zipcode" />
				</div>
				
				<div class="form-group col-md-6">
					<label for="address">Endereço:</label>
					<input type="text" id="address" class="form-control" />
				</div>
				
				<div class="form-group col-md-4">
					<label for="area">Bairro:</label>
					<input type="text" id="area" class="form-control" />
				</div>
				
				<div class="form-group col-md-3">
					<label for="state">Estado:</label>
					<select id="state" class="form-control">
						<option value="">-- Selecione --</option>
						<option value="AC">Acre</option>
						<option value="AL">Alagoas</option>
						<option value="AP">Amapá</option>
						<option value="AM">Amazonas</option>
						<option value="BA">Bahia</option>
						<option value="CE">Ceará</option>
						<option value="DF">Distrito Federal</option>
						<option value="ES">Espírito Santo</option>
						<option value="GO">Goiás</option>
						<option value="MA">Maranhão</option>
						<option value="MT">Mato Grosso</option>
						<option value="MS">Mato Grosso do Sul</option>
						<option value="MG">Minas Gerais</option>
						<option value="PA">Pará</option>
						<option value="PB">Paraíba</option>
						<option value="PR">Paraná</option>
						<option value="PE">Pernambuco</option>
						<option value="PI">Piauí</option>
						<option value="RJ">Rio de Janeiro</option>
						<option value="RN">Rio Grande do Norte</option>
						<option value="RS">Rio Grande do Sul</option>
						<option value="RO">Rondônia</option>
						<option value="RR">Roraima</option>
						<option value="SC">Santa Catarina</option>
						<option value="SP">São Paulo</option>
						<option value="SE">Sergipe</option>
						<option value="TO">Tocantins</option>
					</select>
				</div>
				
				<div class="form-group col-md-3">
					<label for="city">Cidade:</label>
					<input type="text" id="city" class="form-control" />
				</div>
				
				<div class="form-group col-md-2">
					<label for="number">Número:</label>
					<input type="text" id="number" class="form-control" />
				</div>
				
				<div class="form-group col-md-3">
					<label for="details">Complemento:</label>
					<input type="text" id="details" class="form-control" />
				</div>
				
				<div class="form-group col-md-1">
					<label for="details">&nbsp;</label>
					<button class="btn btn-success w-100" aria-label="Adicionar" id="address_add">
						<i class="fa fa-plus"></i>
					</button>
				</div>
			</div>
			<div class="form-row" id="addresses"></div>
			<div class="btn-toolbar">
				<button type="submit" class="btn btn-primary ml-auto">Salvar</button>
			</div>
		</form>
	</section>
</div>

<script>
	let addresses = <?= json_encode((isset($addresses) && $addresses ? $addresses : [])); ?>;
	
	console.log(addresses);

	$(document).ready(function () {
		buildAddresses();
	});


	$(document)
		.on('click', '#address_add', function (event) {
			event.preventDefault();
			
			let address = {
				zip_code: $('#zip_code').val(),
				address: $('#address').val(),
				state: $('#state').val(),
				city: $('#city').val(),
				area: $('#area').val(),
				number: $('#number').val(),
				details: $('#details').val(),
			};

			if(!address.zip_code || !address.address || !address.state || !address.city || !address.area){
				alert('Os campos CEP, Endereço, Estado, Cidade e Bairro são obrigatórios.');
				return false;
			}

			addresses.push(address);
			buildAddresses();

			$('#zip_code, #address, #state, #city, #area, #number, #details').val('');
		})

		.on('click', '[data-remove-address]', function (event) {
			event.preventDefault();
			let index = $(this).data('remove');
			addresses.splice(index, 1);

			buildAddresses();
		});

	function buildAddresses() {
		$('#addresses').html('');

		if (addresses.length > 0) {
			for (const [key, address] of addresses.entries()) {
				
				let source = document.getElementById("address-template").innerHTML;
				let template = Handlebars.compile(source);
				address.index = key;

				$('#addresses').append(template(address));
			}
		}
		
		return;
	}

</script>

<script id="address-template" type="text/x-handlebars-template">
	<div class="col-md-4 address-item">
		<input type="hidden" name="addresses[{{ index }}][zip_code]" value="{{ zip_code }}" />
		<input type="hidden" name="addresses[{{ index }}][address]" value="{{ address }}" />
		<input type="hidden" name="addresses[{{ index }}][state]" value="{{ state }}" />
		<input type="hidden" name="addresses[{{ index }}][city]" value="{{ city }}" />
		<input type="hidden" name="addresses[{{ index }}][area]" value="{{ area }}" />
		<input type="hidden" name="addresses[{{ index }}][number]" value="{{ number }}" />
		<input type="hidden" name="addresses[{{ index }}][details]" value="{{ details }}" />
		<div class="card">
			<div class="card-body">
				<span class="d-block">{{ zip_code }}</span>
				<span class="d-block">{{ address }} {{#if number}}, {{ number }} {{/if}} {{#if details}} ({{ details }}) {{/if}}</span>
				<span class="d-block">{{ area }}</span>
				<span class="d-block">{{ city }} - {{ state }}</span>
			</div>
			<div class="card-footer">
				<button class="btn btn-danger d-block ml-auto" data-remove-address="{{ index }}" aria-label="Remover">
					<i class="fa fa-trash"></i>
				</button>
			</div>
		</div>
	</div>
</script>
