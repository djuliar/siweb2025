<?php if(@$_GET['menu'] == ''){
	include 'content/dashboard.php';
} else if($_GET['menu'] == 'table'){
	include 'content/table.php';
} else {
	include 'content/404.php';
}
?>