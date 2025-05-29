
<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Restablece tu Contraseña en UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/olvide" class="formulario" method="POST" novalidate>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>
          
            <input type="submit" class="boton" value="Restablecer Contraseña">

        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar sesion</a>
            <a href="/crear">¿Aun no tines una cuenta? Crear una</a>
        </div>
    </div>


</div>