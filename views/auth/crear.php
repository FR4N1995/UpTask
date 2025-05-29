
<div class="contenedor crear">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/crear" class="formulario" method="POST">
        <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="nombre" id="nombre" name="nombre" value="<?php echo $usuarios->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $usuarios->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input type="password" id="password2" name="password2">
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">

        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar sesion</a>
            <a href="/olvide">olvidaste tu Password</a>
        </div>
    </div>


</div>