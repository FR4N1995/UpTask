<?php

use Model\Proyecto;

 include_once __DIR__ . '/header-dashboard.php'; ?>
<!-- count funcion de php cuenta cuantos elementos hay en un arreglo  -->
<?php if(count($proyectos)=== 0)  { ?>
        <p class="no-proyecto">No hay Proyectos<a href="/crear-proyecto">Empieza creando Uno</a></p>
        
<?php } else {?>
     <ul class="listado-proyectos">
        <?php foreach($proyectos as $proyecto){ ?>
             <li class="proyecto">
                <a href="/proyecto?url=<?php echo $proyecto->url; ?>">
                    <?php echo $proyecto->nombre_proyecto; ?>
                </a>
             </li>
        <?php } ?>
     </ul>
<?php }?>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

