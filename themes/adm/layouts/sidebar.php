<div id="layoutSidenav_nav">
	<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			<div class="nav">
				<a class="nav-link" href="<?= url('admin/dash'); ?>">
					<div class="sb-nav-link-icon">
						<i class="fas fa-tachometer-alt"></i>
					</div>
					Dashboard
				</a>
				<div class="sb-sidenav-menu-heading">Acesso</div>
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
					<div class="sb-nav-link-icon">
						<i class="fas fa-user"></i>
					</div>
					Usuários
					<div class="sb-sidenav-collapse-arrow">
						<i class="fas fa-angle-down"></i>
					</div>
				</a>
				<div class="collapse" id="collapseUsers" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
					<nav class="sb-sidenav-menu-nested nav">
						<a class="nav-link" href="<?= url('admin/usuarios'); ?>">Gerenciar Usuários</a>
						<a class="nav-link" href="<?= url('admin/usuarios/novo'); ?>">Novo Usuário</a>
					</nav>
				</div>
				<div class="sb-sidenav-menu-heading">Clientes</div>
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCustomers" aria-expanded="false" aria-controls="collapseCustomers">
					<div class="sb-nav-link-icon">
						<i class="fas fa-id-badge"></i>
					</div>
					Clientes
					<div class="sb-sidenav-collapse-arrow">
						<i class="fas fa-angle-down"></i>
					</div>
				</a>
				<div class="collapse" id="collapseCustomers" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
					<nav class="sb-sidenav-menu-nested nav">
						<a class="nav-link" href="<?= url('admin/clientes'); ?>">Gerenciar Clientes</a>
						<a class="nav-link" href="<?= url('admin/clientes/novo'); ?>">Novo Cliente</a>
					</nav>
				</div>
			</div>
		</div>
		<div class="sb-sidenav-footer">
			<div class="small">Logado como:</div>
			<?= user()->name; ?>
		</div>
	</nav>
</div>
