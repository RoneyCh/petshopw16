
<!doctype html>
<html lang="en">
<head>
<title>Petshop W16</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">
<meta name="robots" content="noindex, follow">
<style>
    body {
        background-color: #EEE;
    }
</style>
</head>
<body>
<div class="wrapper d-flex align-items-stretch">
<nav id="sidebar">
<div class="custom-menu">
<button type="button" id="sidebarCollapse" class="btn btn-primary">
<i class="fa fa-bars"></i>
</button>
</div>
<div class="p-4 pt-5">
<h1><a href="#" class="logo">Petshop</a></h1>
<ul class="list-unstyled components mb-5">
<li class="active">
<a href="">Home</a>
</li>
<li>
<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Cadastrar</a>
<ul class="collapse list-unstyled" id="pageSubmenu">
<?php if(isset($_SESSION['usuario']['des_cargo_usr']) && $_SESSION['usuario']['des_cargo_usr'] == 1) { ?>
<li>
<a href="#" onclick="loadView('Usuario/listUsuarios')">Usuários</a>
</li>
<?php } ?>
<li>
<a href="#" onclick="loadView('Pets')">Pets</a>
</li>
<li>
<a href="#" onclick="loadView('Agendamentos')">Agendamentos</a>
</li>
</ul>
</li>
<li>
<a href="#" onclick="loadView('Conta')">Conta</a>
</li>
<li>
<a href="Usuario/logout">Sair</a>
</li>
</div>
</nav>

<div id="content" class="p-4 p-md-5 pt-5">
</div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8b253dfea2ab4077af8c6f58422dfbfd1689876627854" integrity="sha512-bjgnUKX4azu3dLTVtie9u6TKqgx29RBwfj3QXYt5EKfWM/9hPSAI/4qcV5NACjwAo8UtTeWefx6Zq5PHcMm7Tg==" data-cf-beacon='{"rayId":"8094a3136cb90110","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2023.8.0","si":100}' crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        loadView("Home/home");
    });


    let dados = [];
    let table = [];
    function loadView(view) {
        $.ajax({
        url: view,
        type: 'GET',
        success: function(data) {
        $('#content').html(data);
        },
        error: function() {
        alert('Erro ao carregar a view');
    }   
});
}

</script>
</body>
</html>