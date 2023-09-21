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
    <h4 class="text-center"><?= (!empty($dados)) ? "Editar Usuário" : "Cadastro de Usuário"; ?></h4>
        <form id="userForm">
                            <div class="mb-3">
                                <?php $nome_usuario = isset($dados->des_usuario_usr) ? $dados->des_usuario_usr : ""; ?>
                                <label for="des_usuario" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="des_usuario" name="des_usuario" value="<?= $nome_usuario ?>" required>
                            </div>
                            <div class="mb-3">
                                <?php $email_usuario = isset($dados->des_email_usr) ? $dados->des_email_usr : ""; ?>
                                <label for="des_email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="des_email" name="des_email" value="<?= $email_usuario ?>" required>
                            </div>
                            <?php if (empty($dados) || $dados->des_email_usr == $_SESSION['usuario']['des_email_usr']) { ?>
                                <div class="mb-3">
                                    <label for="des_senha" class="form-label">Senha:</label>
                                    <input type="password" class="form-control" id="des_senha" name="des_senha" required>
                                </div>
                                <?php if (!empty($dados)) { ?>
                                <div class="mb-3">
                                    <label for="des_senha_nova" class="form-label">Nova senha:</label>
                                    <input type="password" class="form-control" id="des_senha_nova" name="des_senha_nova" required>
                                </div>
                            <?php }} ?>
                            <?php if (isset($_SESSION['usuario']['des_cargo_usr']) && $_SESSION['usuario']['des_cargo_usr'] == 1) { ?>
                                <div class="mb-3">
                                    <label for="des_cargo" class="form-label">Cargo:</label>
                                    <select class="form-select" id="des_cargo" name="des_cargo">
                                        <option value="1" <?= (isset($dados->des_cargo_usr) && $dados->des_cargo_usr == 1) ? "selected" : ""; ?>>Administrativo</option>
                                        <option value="0" <?= (isset($dados->des_cargo_usr) && $dados->des_cargo_usr == 0) ? "selected" : ""; ?>>Cliente</option>
                                    </select>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <button type="button" id="submitButton" class="btn btn-primary rounded p-3 p5"><?= (!empty($dados)) ? "Editar" : "Cadastrar"; ?></button>&nbsp;&nbsp;&nbsp;
            <?php if(!isset($_SESSION['usuario'])) { ?>
            <button type="button" class="btn btn-primary rounded p-3 p5" onclick="window.history.back();">Login</button>
            <?php } ?>
                            </div>
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
                    url: '<?= (!empty($dados)) ? $this->base_url."Usuario/atualizarUsuario/".$dados->cod_usuario_usr : $this->base_url."Usuario/criarUsuario" ?>',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                    Swal.fire('Successo', response.success, 'success');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadView('Usuario/listUsuarios');
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
