<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php
        include_once __DIR__ . '/../templates/alertas.php';
    ?>

    <a href="/perfil" class="enlace">Volver al perfil</a>

    <form method="POST" class="formulario" action="/cambiar-password">
        <div class="campo">
            <label for="nombre">Password Actual</label>
            <input type="password" name="password_actual"  placeholder="Password Actual">
        </div>
        <div class="campo">
            <label for="email">Password Nuevo</label>
            <input type="password"  name="password_nuevo" placeholder="Password Nuevo">
        </div>

            <input type="submit" value="Guardar Cambios">
    </form>
</div>





<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

