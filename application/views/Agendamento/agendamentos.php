<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">

<style>
    body {
        background-color: #EEE;
    }
</style>
</head>
<body>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agendamentoModal" id="openModalButton">Novo Registro</button>

    <table class="display table table-bordered" id="myTable" width="100%">
        <thead>
            <tr>
                <th>Nome do Pet</th>
                <th>Espécie</th>
                <th>Agendado por</th>
                <th>Dono</th>
                <th>Data do Agendamento</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>

<div class="modal fade" id="agendamentoModal" tabindex="-1" aria-labelledby="agendamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<?php echo $this->load->view('component_delete'); ?>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
    $("#openModalButton").click(function () {
        $("#agendamentoModal .modal-content").load("<?=$this->base_url?>Agendamentos/showCreate");
    });
    $(".open-modal").click(function (event) {
        let cod_agendamento = $(this).data("cod-agendamento");
        $("#agendamentoModal .modal-content").load("<?=$this->base_url?>Agendamentos/showCreate/" + cod_agendamento);
    });
    $(".deleteAgendamento").click(function () {
        let cod_agendamento = $(this).data("cod-agendamento");

        $("#confirmDelete").data("cod-agendamento", cod_agendamento);
    });

    $("#confirmDelete").click(function () {
        let cod_agendamento = $(this).data("cod-agendamento");

        $.ajax({
            type: "POST",
            url: "<?=$this->base_url?>Agendamentos/deletarAgendamento/" + cod_agendamento,
            success: function (response) {
                if (response.success) {
                    Swal.fire('Sucesso', response.success, 'success');
                } else if (response.error) {
                    Swal.fire('Erro', response.error, 'error');
                } else if(response.warning) {
                    Swal.fire('Aviso', response.warning, 'warning');
                }
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                loadView('Agendamentos/listAgendamentos');
            },
            error: function (xhr, status, error) {
                Swal.fire('Erro', 'Ocorreu um erro ao excluir o item.', 'error');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }
        });
    });

});
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
    dados = <?=  json_encode($dados) ?>;
    table = new DataTable('#myTable', {
        language: {
            info: "Mostrando _START_ até _END_ de _TOTAL_ registros",
            search: "Pesquisar",
            infoFiltered: "(filtrado de _MAX_ registros)",
            zeroRecords: "Nenhum registro encontrado",
            paginate: {
                first: "Primeiro",
                last: "Último",
                next: "Próximo",
                previous: "Anterior"
            },
            lengthMenu: "Mostrar _MENU_ registros",
            infoEmpty: "Nenhum registro disponível",
        },
        data: dados,
        columns: [
        { data: 'des_nome_pet' },
        { data: 'des_especie_pet' },
        { data: 'agendado_por'},
        { data: 'dono_pet'},
        { data: 'dta_agendamento_agd'},
        { 
            data: 'cod_agendamento_agd',
            render: function(data) {
                return `<a href="#" class="open-modal" data-bs-toggle="modal" data-bs-target="#agendamentoModal" data-cod-agendamento="${data}"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="#" class="deleteAgendamento" data-toggle="modal" data-target="#deleteConfirmationModal" data-cod-agendamento="${data}"><i class="fas fa-trash-alt"></i></a>`;
            }
        }
    ],
        dom: 'Bfrtip',
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 linhas', '25 linhas', '50 linhas', 'Mostrar todos']
        ],
        pageLength: 10,
        responsive: true,
        
    });

</script>
</html>

