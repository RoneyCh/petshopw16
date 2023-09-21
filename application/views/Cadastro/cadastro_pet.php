<!DOCTYPE html>
<html>
<head>
    <title><?= (!empty($dados)) ? "Editar Usuário" : "Cadastro de Usuário"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> 
    <style>
        body {
            background: #eee;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="container">
    <h4 class="text-center"><?= (!empty($dados)) ? "Editar Pet" : "Adicionar Pet"; ?></h4>
        <form id="userForm">
            <div class="mb-3">
                <?php $nome_pet = isset($dados->des_nome_pet) ? $dados->des_nome_pet : ""; ?>
                <label for="pet_nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="pet_nome" name="pet_nome" value="<?= $nome_pet ?>" required>
            </div>
            <div class="mb-3">
                <?php $especie_pet = isset($dados->des_especie_pet) ? $dados->des_especie_pet : ""; ?>
                <label for="pet_especie" class="form-label">Especie:</label>
                <input type="text" class="form-control" id="pet_especie" name="pet_especie" value="<?= $especie_pet ?>" required>
            </div>
            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['des_cargo_usr'] == 1) { ?>
                <div class="mb-3">
                    <label for="pet_dono" class="form-label">Dono:</label>
                    <select class="form-select" id="pet_dono" name="pet_dono">
                        <option value="">Selecione um dono</option>
                        <?php foreach ($usuarios as $usuario) { ?>
                            <option value="<?= $usuario->cod_usuario_usr ?>" <?= (isset($dados->cod_usuario_pet) && $dados->cod_usuario_pet == $usuario->cod_usuario_usr) ? "selected" : ""; ?>><?= $usuario->des_usuario_usr ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>
            <button type="button" id="submitButton" class="btn btn-primary"><?= (!empty($dados)) ? "Editar" : "Cadastrar"; ?></button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>

        $(document).ready(function() {
            $("#submitButton").click(function() {
                var formData = $("#userForm").serialize();

                $.ajax({
                    type: 'POST',
                    url: '<?= (!empty($dados)) ? $this->base_url."Pets/atualizarPet/".$dados->cod_pets_pet : $this->base_url."Pets/criarPet" ?>',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                    Swal.fire('Successo', response.success, 'success');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadView('Pets/listPets');
                } else if (response.error) {
                    Swal.fire('Erro', response.error, 'error');
                } else if(response.warning) {
                    Swal.fire('Aviso', response.warning, 'warning');
                    
                }
                },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', xhr.responseText, 'error');
                    }
                });
            });
        });

        function loadView(view) {
    $.ajax({
        url: view,
        type: 'GET',
        success: function(data) {
            $('#content').html('');
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
