<div class="contenedor crear">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>


        <?php if ($mostrar) { ?>
            <p class="descripcion-pagina">Restablecer contraseña</p>
            <form class="formulario" method="POST">

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña">
                </div>

                <div class="campo">
                    <label for="password2">Confirmar contraseña</label>
                    <input type="password" name="password2" id="password2" placeholder="Ingresa tu contraseña">
                </div>

                <input type="submit" value="Restablecer contraseña" class="boton">


            </form>
        <?php }else{?>
            <div class="acciones">
                <a href="/">Iniciar sesion</a>
            </div>
        <?php }?>
    </div>
</div>