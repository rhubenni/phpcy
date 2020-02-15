<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

AuthController\AC::logout();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- {%headers} -->
        <title>Login</title>
        <!-- {%assets} -->
        <link href="/assets/normalize-8.0.1/normalize.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/bootstrap-4.1.3/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/jquery-3.4.1/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/bootstrap-4.1.3/bootstrap.bundle.min.js" type="text/javascript"></script>
        <link href="/assets/fontawesome-free-5.12.1-web/css/all.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/css/authcontroller.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <!-- {%body} -->
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="vertical-center">
                    <div id="cy-authcontroller-loginform" class="text-center shadow">
                        Digite suas informações de login: <br />&nbsp;
                        
                        <form class="form-inline login-form">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input id="cy-authcontroller-loginuser" type="text" class="form-control" placeholder="Usuário" required>
                                &nbsp;&nbsp;&nbsp;
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input id="cy-authcontroller-loginpass" type="password" class="form-control" placeholder="Senha" required>
                                &nbsp;&nbsp;&nbsp;
                                <button id="cy-authcontroller-doLogin" type="submit" class="btn btn-primary">Login &gt;&gt;</button>
                            </div>
                        </form>
                        <div id="cy-authcontroller-msg" class="alert alert-warning m-2" role="alert">
                            Acesso somente a usuários autenticados.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        
        $("#cy-authcontroller-loginform form").submit(function(e) {
            
            var request = {
                action: 'login',
                credentials: {
                    user: $("#cy-authcontroller-loginuser").val(),
                    pass: $("#cy-authcontroller-loginpass").val()
                }
            };
            $.ajax({
                url: '/api/authcontroller',
                type: 'POST',
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(request),
                async: false
            }).done(function(data){
                    console.log(data);
                if(data.pass == true) {
                    document.location.href = data.go;
                } else {
                    $('#cy-authcontroller-msg').html(data.message);
                    $('#cy-authcontroller-msg').css("display", "block");
                }
            });
            e.preventDefault();
        });
        
        </script>
    </body>
</html>