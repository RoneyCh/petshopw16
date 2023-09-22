<!DOCTYPE html>
<html>
<head>
    <title><?= (!empty($dados)) ? "Editar Usuário" : "Cadastro de Usuário"; ?></title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">

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
    <h4 class="text-center"><?= (!empty($dados)) ? "Editar Agendamento" : "Adicionar Agendamento"; ?></h4>
        <form id="userForm">
            <div class="mb-3">
                <label for="cod_pet" class="form-label">Pet:</label>
                <select class="form-select" id="cod_pet" name="cod_pet">
                    <option value="">Selecione um pet</option>
                    <?php foreach ($pets as $pet) { ?>
                        <option value="<?= $pet->cod_pets_pet ?>" <?= (isset($dados->cod_pets_agd) && $dados->cod_pets_agd == $pet->cod_pets_pet) ? "selected" : ""; ?>><?= $pet->des_nome_pet . ' - ' . $pet->des_especie_pet ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <?php $agendamento = isset($dados->dta_agendamento_agd) ? $dados->dta_agendamento_agd : ""; ?>
                <label for="data_agendamento">Data de Agendamento</label>
                <input type="text" id="data_agendamento" name="data_agendamento" autocomplete="off" value="<?php echo $agendamento; ?>">
            </div>
            
            <button type="button" id="submitButton" class="btn btn-primary"><?= (!empty($dados)) ? "Editar" : "Cadastrar"; ?></button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>

        $(document).ready(function() {
            $("#data_agendamento").datetimepicker({
                format: 'd/m/Y H:i',
                lang: 'pt',
                
            });
            $("#submitButton").click(function() {
                var formData = $("#userForm").serialize();

                $.ajax({
                    type: 'POST',
                    url: '<?= (!empty($dados)) ? $this->base_url."Agendamentos/atualizarAgendamento/".$dados->cod_agendamento_agd : $this->base_url."Agendamentos/criarAgendamento" ?>',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                    Swal.fire('Successo', response.success, 'success');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadView('Agendamentos/listAgendamentos');
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
