        <div class="pagetitle">
			<h1><?php echo (@$_GET['menu'] == '' ? "Dashboard" : ucfirst(@$_GET['menu'])); ?></h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="./">Home</a></li>
					<li class="breadcrumb-item active"><?php echo (@$_GET['menu'] == '' ? "Dashboard" : ucfirst(@$_GET['menu'])); ?></li>
				</ol>
			</nav>
		</div><!-- End Page Title -->