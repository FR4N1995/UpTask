
<div class="contenedor restablecer">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca Tu nueva Password</p>
        <?php if ($mostrar):
            # code...
        ?>
        <!-- Si queremos tener el token de referencia en la url o cualquier otro dato debemos quitar el accion del FORM -->
        <form class="formulario" method="POST">
  
            <div class="campo">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password">
            </div>
          
            <input type="submit" class="boton" value="Restablecer Contraseña">

        </form>
        <?php endif ?>
        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar sesion</a>
            <a href="/crear">¿Aun no tines una cuenta? Crear una</a>
        </div>
    </div>


</div>