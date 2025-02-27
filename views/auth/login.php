<div class="contenedor login">
<?php include_once __DIR__.'/../templates/nombre-sitio.php'?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesion</p>
        <?php include_once __DIR__.'/../templates/alertas.php'?>
        <form class="formulario" method="POST" action="/" novalidate>
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Ingresa tu e-mail">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña">
            </div>

            <input type="submit" value="Iniciar sesion" class="boton">


        </form>

        <div class="acciones">
            <a href="/crear-cuenta">¿Desear crear una cuenta?</a>
            <a href="/olvido-password">¿Olvido su contraseña?</a>
        </div>
    </div>
</div>