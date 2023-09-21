<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #eee;
        }
        .ftco-section {
            padding: 50px 0;
        }
        .login-wrap {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .login-form .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        .login-form input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }
        .login-form .form-control.rounded-left {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }
        .login-form .form-control.rounded-right {
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        .login-form .checkbox-wrap {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }
        .login-form .checkbox-wrap input[type="checkbox"] {
            margin-right: 5px;
        }
        .login-form .checkbox-wrap .checkmark {
            position: relative;
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #ccc;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .login-form .checkbox-wrap input[type="checkbox"]:checked + .checkmark:after {
            content: '\f00c';
            font-family: FontAwesome;
            position: absolute;
            top: -1px;
            left: 4px;
            font-size: 14px;
            color: #007bff;
        }
        .login-form .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 15px 20px;
            border-radius: 20px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-form .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Login</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>
                        <h3 class="text-center mb-4">Já tem uma conta?</h3>
                        <form method="post" action="<?= $this->base_url . "Usuario/login"?>">
                        <div class="form-group">
    <input type="text" class="form-control rounded-left" id="des_email" name="des_email" placeholder="Email">
    <div id="email-error" class="text-danger"></div>
</div>
<div class="form-group d-flex">
    <input type="password" class="form-control rounded-right" id="des_senha" name="des_senha" placeholder="Senha">
    <div id="senha-error" class="text-danger"></div>
</div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary rounded submit p-3 px-5">Entrar</button>
                                <button type="button" id="cadastrar" class="btn btn-primary p-3 px-5">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8b253dfea2ab4077af8c6f58422dfbfd1689876627854" integrity="sha512-bjgnUKX4azu3dLTVtie9u6TKqgx29RBwfj3QXYt5EKfWM/9hPSAI/4qcV5NACjwAo8UtTeWefx6Zq5PHcMm7Tg==" data-cf-beacon='{"rayId":"809ccbc77e2900fd","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2023.8.0","si":100}' crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#cadastrar').click(function(e) {
            e.preventDefault();
            window.location.href = '<?= $this->base_url . "Usuario/showCreate"?>';
            });

            $('form').submit(function(e) {
            e.preventDefault(); // Impede o envio do formulário padrão

            $('#email-error').text('');
            $('#senha-error').text('');

            var email = $('#des_email').val();
            var senha = $('#des_senha').val();

            if (email === '') {
                $('#email-error').text('Email é obrigatório.');
                return;
            }

            if (senha === '') {
                $('#senha-error').text('Senha é obrigatória.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: '<?= $this->base_url . "Usuario/login"?>',
                data: { des_email: email, des_senha: senha },
                success: function(response) {
                    window.location.href = 'Home';
                },
                error: function() {
                    Swal.fire('Erro', 'Ocorreu um erro ao realizar o login.', 'error');
                }
            });
        });
        })

        
    </script>
</body>
</html>
