<div class="contenedor crear">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear una cuenta</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <form class="formulario" method="POST" action="/crear-cuenta">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingresa tu nombre" value="<?php echo $usuario->nombre; ?>">
            </div>            

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Ingresa tu e-mail" value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" >
            </div>

            <div class="campo">
                <label for="password2">Repetir contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu contraseña">
            </div>

            <input type="submit" value="Crear cuenta" class="boton">


        </form>

        <div class="acciones">
            <a href="/">Iniciar sesion</a>
            <a href="/olvido-password">¿Olvido su contraseña?</a>
        </div>
    </div>
</div>