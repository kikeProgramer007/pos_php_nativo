<div id="back"> </div>

<div class="login-box">
  <div class="login-logo">

  
  <!-- este login sera para cabañas el gallito camba -->
    
<!--   <img src="vistas/img/plantilla/logo-blanco-bloque2.png" class="img-responsive" style="padding:30px 100px 0px 100px"> -->


  <!-- este login sera para pollos rossyy -->
     <img src="vistas/img/plantilla/logo-blanco-bloque.png" class="img-responsive" style="padding:10px 50px 0px 50px"> 
  </div>
  
  <p class="login-box-msg">¡ BIENVENIDO  INGRESA TU CUENTA !</p>

  <!-- Título de bienvenida centrado -->
  <div class="text-center" style="margin-top: -12px; font-size: 14px; font-weight: bold;color: black; /* Añadido para hacer el texto en negrita */">
 SOLO PERSONAL AUTORIZADO
</div>



  <div class="login-box-body">
    <form method="post">

      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback" style="margin-top: 20px; position: relative;">
        <input id="passwordInput" type="password" class="form-control" placeholder="Contraseña" name="ingPassword" required>
      
        <!-- Botón/ícono para mostrar/ocultar contraseña -->
        <span id="togglePassword" style="position: absolute; right: 10px; top: 8px; cursor: pointer; z-index: 2;">
          <span class="glyphicon glyphicon-eye-open"></span>
        </span>
      </div>

      <div class="row">
        <div class="col-xs-12 text-center" style="margin-top: 20px;">
          <button type="submit" class="btn btn-block btn-flat mx-auto" style="border-radius: 10px; background: black; color: white;">
            INICIAR SESIÓN
          </button>
        </div>
      </div>

      <?php
        $login = new ControladorUsuarios();
        $login->ctrIngresoUsuario();
      ?>

    </form>
  </div>
</div>
<script>
  (function() {
    var input = document.getElementById('passwordInput');
    var toggle = document.getElementById('togglePassword');
    if (!input || !toggle) return;

    toggle.addEventListener('click', function() {
      var isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      // Cambia el icono si tu plantilla tiene ambos iconos disponibles
      var icon = toggle.querySelector('.glyphicon');
      if (icon) {
        if (isPassword) {
          icon.classList.remove('glyphicon-eye-open');
          icon.classList.add('glyphicon-eye-close');
        } else {
          icon.classList.remove('glyphicon-eye-close');
          icon.classList.add('glyphicon-eye-open');
        }
      }
    });
  })();
</script>
