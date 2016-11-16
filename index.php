<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <title>La Vendimia</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/toastr.min.css">
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/toastr.min.js"></script>
    <script src="/js/index.js"></script>
</head>
<body>
    <header>
        <div class="company-name pull-right">
            <label>La Vendimia</label>
        </div>
        <div class="clearfix"></div>
        <nav>
            <div class="dropdown">
                <button class="dropbtn" onclick="showMenu()">Inicio</button>
                <div id="menu" class="dropdown-content">
                    <a href="/ventas">Ventas</a>
                    <a href="/clientes">Clientes</a>
                    <a href="/articulos">Articulos</a>
                    <a href="/config">Configuración</a>
                </div>
            </div>
            <div class="time pull-right">
                <label>Fecha: <?php echo (new DateTime("now", new DateTimeZone('America/Mazatlan')))->format("d/m/Y"); ?></label>
            </div>
        </nav>
    </header>

    <div class="content">
        <?php
            $view = $_SERVER["REQUEST_URI"];
            $view = explode("?", $view)[0];
            
            switch($view){
                case "/ventas":
                    include "views/lista_ventas.html";
                break;
                case "/ventas/agregar":
                    include "views/registro_ventas.html";
                break;
                case "/clientes":
                    include "views/lista_clientes.html";
                break;
                case "/clientes/agregar":
                    include "views/registro_clientes.html";
                break;
                case "/clientes/editar":
                    include "views/registro_clientes.html";
                break;
                case "/articulos":
                    include "views/lista_articulos.html";
                break;
                case "/articulos/agregar":
                    include "views/registro_articulos.html";
                break;
                case "/articulos/editar":
                    include "views/registro_articulos.html";
                break;
                case "/config":
                    include "views/config_general.html";
                break;
                default:
                    include "views/inicio.html";
                break;
            }
        ?>
    </div>
</body>
</html>