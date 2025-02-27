<div class="contenedor olvide">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Olvido su contraseña</p>
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <form class="formulario" method="POST" action="/olvido-password">        

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="text" name="email" id="email" placeholder="Ingresa tu e-mail">
            </div>

            <input type="submit" value="Enviar correo" class="boton">


        </form>

        <div class="acciones">
            <a href="/">Iniciar sesion</a>
            <a href="/crear-cuenta">¿Desear crear una cuenta?</a>
        </div>
    </div>
</div>