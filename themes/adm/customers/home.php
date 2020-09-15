<?php $v->layout("_admin"); ?>

<div class="container">
	<h1 class="mt-4">Clientes</h1>

	<form action="<?= url('/admin/clientes'); ?>" method="post">
		<div class="form-row">
			<div class="form-group col-8 col-md-10">
				<input type="s" name="s" id="s" value="<?= $search; ?>" class="form-control" placeholder="Buscar Clientes" />
			</div>
			<div class="form-group col-4 col-md-2">
				<button type="submit" class="btn btn-primary w-100">Buscar</button>
			</div>
		</div>
	</form>

	<?php if ($customers): ?>
		<div class="row row-cols-1 row-cols-md-3">
			<?php foreach ($customers as $customer):
				?>
				<div class="col mb-4">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><?= $customer->name; ?></h5>
							<ul class="list-group list-group-flush">
								<li class="list-group-item px-0">
									<span class="font-weight-bold">Nome:</span>
									<span><?= $customer->name; ?></span>
								</li>
								<li class="list-group-item px-0">
									<span class="font-weight-bold">Telefone:</span>
									<span><?= phone_format($customer->phone); ?></span>
								</li>
							</ul>
						</div>

						<div class="card-footer">
							<div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
								<a href="<?= url("admin/clientes/deletar/{$customer->id}"); ?>" title="Excluir" class="btn btn-danger">Excluir</a>
								<a href="<?= url("admin/clientes/editar/{$customer->id}"); ?>" title="Editar" class="btn btn-primary">Editar</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="text-center">
			<?= $paginator; ?>
		</div>
	<?php else: ?>
		<div class="alert alert-warning" role="alert">
			Nenhum cliente encontrado
		</div>
	<?php endif; ?>
</div>

<script>

	$('.btn-danger').on('click', function (e) {
		if (!confirm('Tem certeza que quer excluir o cliente?')) {
			e.preventDefault();
		}
	});
</script>
