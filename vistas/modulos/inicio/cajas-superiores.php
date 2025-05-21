<?php

$item = null;
$valor = null;
$orden = "id";





$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$meseros = ControladorMeseros::ctrMostrarMeseros($item, $valor);
$totalMeseros = count($meseros);

$cliente =ControladorClientes::ctrMostrarClientes($item, $valor);
$totalcliente = count($cliente);

$usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
$totalusuarios=count($usuario);


$proveedor = ControladorProveedors::ctrMostrarProveedors($item, $valor);
$totalproveedor=count($proveedor);


$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
$totalProductos = count($productos);

$ventas = ControladorVentas::ctrSumaTotalVentas();
$ventasTotalMesActual = ControladorVentas::ctrVentasTotalMes();
$ventasTotaldDiaActual = ControladorVentas::ctrVentasTotalDia();
?>



<div class="col-lg-3 col-xs-6 text-uppercase ">

  <div class="small-box bg-green monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($ventasTotaldDiaActual["total"], 2) . 'BS'; ?></h3>


      <p>Monto del Día</p>

    </div>

    <div class="icon">
      
     <img src="./vistas/img/plantilla/bs.webp" alt="">

    </div>
    <a href="reporte-venta" role="button" class="small-box-footer">
      <?php echo date('d-m-Y'); ?>
    </a>

  </div>

</div>

<style>
  .bg-cafe {
    background-color: #6f4e28;
    /* Color café */
    color: #fff;
    /* Texto en blanco para contraste */
  }
</style>

<style>
.monto-dia-box .icon img {
  max-width: 200px;
  max-height: 110px;
  object-fit: contain;
  position: absolute;
  right: 20px;
  top: 0%;
  transform: translateY(-80%);
  opacity: 0.85;
}
.monto-dia-box .icon {
  position: relative;
  height: 0px;
}
</style>


<div class="col-lg-3 col-xs-6 text-uppercase ">

<div class="small-box bg-cafe monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($ventasTotalMesActual["total"], 2) . 'BS';; ?></h3>

      <p>Total Venta del Mes</p>

    </div>

    <div class="icon">

    <img src="./vistas/img/plantilla/bsmes.webp" alt="">

    </div>

    <?php
    date_default_timezone_set('America/La_Paz');
    ?>

    <a href="ganancias-ventas" role="button" class="small-box-footer">
      <?php echo date('F-Y'); ?>
    </a>

  </div>




</div>

<div class="col-lg-3 col-xs-6 text-uppercase ">

<div class="small-box bg-blue monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($ventas["total"], 2) . 'BS';; ?></h3>

      <p> TOTAL DE VENTAS POR AÑO</p>

    </div>

    <div class="icon">

    <img src="./vistas/img/plantilla/bsyear.webp" alt="">

    </div>

    <?php
    date_default_timezone_set('America/La_Paz');
    ?>

    <a href="ganancias-ventas" role="button" class="small-box-footer">

      <?php echo date('Y'); ?>
    </a>
  </div>

</div>


<div class="col-lg-3 col-xs-5 text-uppercase ">

<div class="small-box bg-red monto-dia-box">

    <div class="inner">

      <h3><?php echo number_format($totalProductos); ?></h3>

      <p>Productos</p>

    </div>

    <div class="icon">

    <img src="./pagina/assets/img/food/22.webp" alt="">

    </div>

    <a href="productos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-3 col-xs-6 text-uppercase">

  <div class="small-box bg-yellow">

    <div class="inner">

      <h3><?php echo number_format($totalCategorias); ?></h3>

      <p>Categorías</p>

    </div>

    <div class="icon">

      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAWfUlEQVR4nO1dCXgb1bWe9LWl63u8rwuUJdACTSEJLSQhDQ1bSOI1JMQJ2ZzlucFNLGuxtY5mNDPad1vyItnaLFnyHjtOUsp7Ld34WqAttK+0PHjwCs0jEEofSQohu3Xed0ZWsOMZOTHUFkX/992PoHvnzplz7j3bPTMmiAIKKKCAAgoooIACPgAMvjWPCPyZJUy/qyQImPVBTFnAVLDvyK1E+/8OEJZnRwjZL4DY+AgQ93VLpzRXAe8DB45/k4geGiTsz6WJ+ieAqHwUiJW9QCyMYOt+P1MXcCnYe/xbROLwEOF6Pk2ofwXE9h8CUTKQFQQQCyJniQXh0kuas4ApYOjYDUTXm8NEw3+nCfI3QHz3MSBWDQKxODYqjHCaWBDeRyyM3DaV6Qu4RNxme8RO0L8F4ns/BeLBYSC+k3hPEAvDewuCmCbUseyNVm/T2ViqH2bv3A/E3ckxgogcIBZFFk4XLQUQBGF2NpzsGtgP8a49sEnWOnJeELeHFxQYNM1gWfbjoY4UJPv2gprmmvn44rbIddNNRwGjUJNGS7J/CNriKUDhZH8vYIZgdjSc6h7YB2aH99RM0VDAKNavX/9PWXWl1DHW7O8FzBDUJFtQV/mEgrrKIxTUVZ5BSTLecGdPwbuaLlRXs5+pVVDL1RTTrOPMf2QtruOOxpaRlrZ4uj3eDbHuAYgm+4Ay2s5OG1H/yGBZ9pMypWGRQs+YNYz5KcbiOGrzNJ3zBSPpUKwLYqkBSPYOQap/GLoG9p1v+P/4O/a3hBMg01D7ZvpZPjSoUamulGvpbRqKHdab7K+ZHY2n3P5gOhBJQKSzDxI9eyDVvxe6+pHZ+zNM34NM38v34ZhAOAEOXyBttrlP6jjLQbXeOFCvY4fiPQMFdXUhdimVX5Zp6c1KkkloKfMLrM19wuUPjgQiiXS4sw/i3Xsg2TuW4Rmmj2N4JAEuf1va4vQDY3am1XpuUEaym2Va7WxCBB9Z76quru7TNXLdYqWeceoM1t9xNievx/3BjnQonoKOrn6e4Rm1ciHDB6EjNQA4zhsIp81O3xnaaHtTTZueqCMNpioZOY9l2Y/hfRib4y28zuRoOPWR9q6kUulldXWaO1QU69AaTM/QFucRk8t/rjEQHcfwrr6sHs8wPTWwj/8d+3FcYyCstrn9Iwaz422NwfwMaTAeDyV6wOTwnL4YOhy+1hGcX8eZn7uYYBAF/qHMXVVXV39CKlXeolBRUi3FPUKaHK+bXb4zDYFwOhDrhEhyrB7PrvD90MUbzgzDw/FuaA3F005fcIRzeIGzekChY4YUSn0ZekJC9+XsnpPIYNrsOnoxdLZGk5DqGwY5zVGTjUV1hXYmb9XVhYbTMpnh3CNsOF2+ABjtXiAZy2E0nLVqZkO1VvsvY+8VindBZ+8gKEjyrlw02T1N51AgJGd9aTL6KyuVn8WDJbQ3Uil7zUWrK8pgIaYN/fBpYvjtW4jhv5V9pv9V8orUC49clXr+4ILQ4/83VcPpbmoDk8M3Qpmch7KGU0qSX8reEndCZ+8Q1Gr0d4iRFU70QGfPIEi12pxn0t7m9jTSoDZwP5rsUWVauhLpb493wcypq3b4BDH87lXE3mMLPjX41y1f7nqxZXb82f+8Ov7c25cn/pSelXgdiOghINoOAtH8MhCNLwHheh4WNz0lajgbRg0nZbL9RUOZflavo9lqiXY+Gk5Xc4BnkFIvzqDwRQgkkuyBeM8g1NbWfyPX4/nbO9JoZ1Qk45+MFVrK9DPcsZ6WtvT0q6vEGzEicuiVj7W/AkTLy0D4XgLC/QIQtueAMD0LhOG3wFdLqJ4CQvFLICSPA1H9UyCqfgTE1n+Hubv7R2iT/U0lxf1EoaJUknrd103WjL42WBxvid3W5W/lBaIyGH/+/gTSx++6eoq6NtdjBmMZtSLX6ndMxhLG6noHmczZPCdyjcMd0R7r4m2NljR+QOoKa4SQ0bWPZyojqh4DYtt/ALHpB0CsPwDEmr1AlO8BoqgPiGXdmcP6JXEgFo3WEy2M9E98IPc7yGzG5n5H7LYuX4D3YrQG05PvQyCzYsmMipRK31N1Qgh19kCiZwgUavWkxQWupszuVRtMv8o1TkUabSiMtmjXB6iuivuBuL8biHuSQNyJjI6+V7x1US2cuHBKgzXjwxut7pNit3U2tPAC0RiMv52qQFiW/WSsux86ugbQ+P5zLlc61jUA8e5+2C6XXz4ZS9piKX7VK0hOMv3e1SUxX1Agay+c0mC0vs4HVc6GM2K3dTS28F6PmrU8O1WBKJXKzyKj0RXGgFFsHoWO/RratkiqF410zoJmiU73BbSBKGSJRPKF6feuLlEAl90ZhWuKY/DxRaHvEwsjFUJTkoz1ZWS2zeMXzXpi0o5XWZzl+akKRC6XX45lNsjAXCpDoqbLk31D0N4xudekIBk5utHNoRhgohHTMHVaA1dPMgm1wfhjzPJSJvthkrGc/vt4VwvDp8cy/OOLI3DZ4va/EAsjPyQWRNqJBWEdsTC8gVgQvoP4VvRL6GYm+oagVkktE5tSZzD9AZlt9zafExtj8TadxTF6g+VPUxVITY3qynjPHkA7kl35uFMUOt3XpHVUsUxNKlUk61Lqmd8b7Q3AWlxgsLqOmGzuU1aP/6zL1zbS0BpKt7TH+aA0HO/iXXJUg2iXUDC4A8Zld0djJpPLC3+fYBALfBeEtxOLInfrbK0j6BrqOasok1pCHaP6VXybqg3GJ5F4lz8wIjbG7Gw8kwnUbAeRmbt1un/dpSTnydRseb2WkSpJptFkc/NRuI61vUFZHEc5m/ekyek742hsHcFA0x8MpZGeYCwJaLQx/Y3qK9E9yC8apJOPhfh4KNvGRP/n3fXhTOsb5q+Ldw0CBobhzl5eSIFoMt0UjKY9zW1pXGRmV8PpaQkGaaPtf3hG+oKijPQ0B9OYxlBR3E/Grch67W38qtTSSrXe+KrR0QC00ZomOetBg8V1xOJqPGlvaDrnbW5LNwbC6WC4gw+8Ip29EEv18RV+iZ5MQJkauypHV+S4f4/+P5+/Gk2poI1Ao41z4akdqqdAuBP8wSh4mtvA0RgAu6cJ7dpJ2uI8puOsr2tZywtaxvITpY4Jy7R0nVRpWGXzNqVxDhVtekyMByo9Z01OR+6qXktX4cNhOoI22d9grM7jZrfvtM3bdM7la003BjIrMhBLQns8CeFkZlWOXZHnUyLjovT9F6zQTP/5FdmzBzq6UPUM8Cs9EE1CU1ss3dgSBqc/ABZX4zmj1XPSYHEcITnrn3Ws+WkNyQ4rtExXQ3MIGlpCUKPQzMVdJmS0KbPj8GRORhZ48tfZNwQyDb1RbIzJ2XB6WlLtKG1cXai3m9oT407FsiuSPx3r28vr13j3IK8iUO+iEAPRTmhuj0Fjayjt8gfA6vWDye45w5idx3VGx5ta1vyCijI9ptQzSZIxnfO2hoBkbcfwnAFdU+ISbYhErr0XmYdMzPVcjM19gg9Ura5jucbVKKnr0HZEU/1QXV39GTHvCnfftOWuXP5MUERy1jRjd75Dmeyv6xjLH1S08cdKkktoKOPhpvYOMLsaRyRq9VXbWfZTF84h17ImFFxzKC6aemBtngyTzK4jYmPCkwhEpqPL8eh0spyT3e3nHQgdZ30517h6DWNEugOhJMy4uspCb7K9hmoF1ZRQf73eEOkaGAZ/ICLK7DqdQYaqKxARfzDO6jqOTGIt7mNTdnt1zAZcqRgp53omjy+zyDQ097Nc4/Sc+Xm0Txi0zri6ykJBGjR8oXBMmJlKNbUVffpgNAW5sqVomNH7ERvDWN1v8+kVq3h6JTyJQKRaeieuVswc53qmxmAkjStfqWfbco0zuxpPo0D0Ijm4rLpKTWeqffv27Z+Kpnp5r0VGUUsv7EePCrOr6PtnjzsvhFyjL+mcJH1tsLiOZfJdznenHKmr6Tp0DCYTSGusk3c25CSzO9c4zE7z2QOKG8oLdZWFp7WdT1VrafMvhFZJNNnPe0YajfDhDTIQjT4GkWL3oM3OtzIZVe/JqQqkjqSYVP8+tFU5BRJJZOZR6QxLcgybFU50Q7J3EHarqJVi6goFNu0ng4zdcQTtiNntF3QT29DL6N0LMpVeMHWyS66ew+eOOvnckSAMZvtf+YjXLv5w4ckEomWcqIp8wY50riNjdKc7ujEBKRXNCFfXaecjzVjohknLvFBXWShJ2s17GxHhlYcxAq6UepJuFk1pdO/hgzQxtUaZbIf51IOz4fRUBVJPUkHcyb4cDoZMy85G9zya7BWlBaGimGYsnGgOCQt3xtQVQi5nL0dfHNWSTEnOu7Df7QuOYLSuZS1PCV1fpVZ/vgMFkiMLS3K2QygQS45gLTyZDSG5BC6MhlZxgaD6wTlCkyQW9UbbQaQHswl5pa6yQLeWdxUp84EJxDm8pzIxhONNoWtxy6NAMYU9Gj1PAMla/4wMsLobpyyQej0ziHN4m8SPWuU6Wou7vTXcmVMgVk/TWZyLMtteyyt1lQVj9RzH9IbZ5eMj8Y9ys3lbYMbrrupIpgN1aksozif9PsrN5G4APhi0e2au7koiUV+Fnkk8NQAShfp2PLPOtnoNFcRkYGNrJD3297EN81odyT6QqelyoX4NbXwObZTV1TQiNkcw0gmxZD9I6vVFwnOYnkHnweZtEZ2D5Myv4xijw3tGbIxUoV8V6+zng2GZjLxibB86KDOurrJoao9BamAY1JQxMSGHNMkJHAaF6BrL1eQaoX4da3o6k6ZoHZmqDVEbzL9HW+ZoED8IYy0ePkVjsLjeFhujNHBJtDP+YDQt5F3NuLrKwuTEcp79YLQ6j19Ymci7kqk+zIp+QuhaTK2gmyhTUt8V6tfRxidRIG5fYMoC0RutLyCzLW5hzwhhdvpHD8KseBAmCMpof4N3wV2+M6K5q5lUV1moKHY/JhJ9gQkrZxaepmEgVV1LChaotYbj/HmHTGvQCc5Nc3wxmtsXSE9VICR6asgsl3AAi3CPZq/VetMvxcZgIpX3sIzjT0qnPdU+GXbJ6TmZvNUAYDXG2L5grJMnVGwHNIVifEJPoWFdQv0qivthCgWSw2UNTyYQo/21yQ6eMGjkg1i9ISY2pjWc4KvplVrGLaSuUCgzrq6yaI10QmpgL6j1jHfs73gMi1GykmIm1GUhfG1h/qhXoTdEhPo1FLsfr8ea26kKxGC2v4nM5nIEa+gY4DFvncYgE42ZsNiuZw/U1o8vR80rdZWF2YXFCPuBttjHlfE7GptHME4hWcsfha7zNqNA9oGKYgaF+tUUu4cXSEtoygKhzc6jmYI8j2iCsj2eOQGVy7X3CvXX0+igDEIoc+o4K2/VVRYq2vQ4n54IhMcxzmTNHItiwYDQdR5/phhCTRt/LNSvpNkUXo+u85R3iNUzeqbiEjxTQTWDxwQd3YNQrVB8RWiMmmS/n6Fj/PPlpbpCSFSGJXwWtLMPsFIw+ztlsh7EB7G6J3omCIc/8waSlrX8Wqi/XkdHUGD+4NQFwtic7/ICEVkUeMSMqgiPC3DFC43hLI6jmfqq8a+u5aW6ygJP/tCFNdrcJ1mb8zg2zuo6h6U+RrsHsr+NbUa7J83329wjQv2c1XUmc71X8HoW58B+RwOwVtcJwX6bm78HZ3OnBeewuk6cn0PkHozVmdZzFmAsjnNjfw+NqiuN3mQm8g12T6ZIAF3gf7TGWlw8493+YKaE6Xzffj5yzyt1lYW0znAPvnw5cYW5BFddts9oc5/KNcZo85zkrO4TufqNNvcZ8Xt4Ru8h1p+ZIxcNtMl2CtUxagCd0XZ+l3BW1ztKwwf1zkcBlwQ9Z38R7Vmooxvq6uj5l3Z1AR848CQRi67RuNu8ftE0TAHTCJVWvwNfa0CvTk1xXdN57wJEwJhdRzCd0xpJwNi3gQuYIdTU1HwOy2RHP6kxLsNdwAxBYzA6Enwh+R6Q65lJv+JQwDTA5Mq8zOlvi6XXr18/oUargGmGVqud3Rbr5L+vQptch6b7/gUIQMsYD+C7J5icVJD0OqExBUwznI3NI92D+8Hj5w/RCn8faqZRT1FLsT4ZTz51BpPoJ0AKmEboR1+ExffWC7skD1BVpf58tLOX/+KQTGco/O2PfEBrKM6n5mWkcAVNAdMIfDs4kj25VIh/vaKAaQLJWn+deTO3M+f7JQX8nYFn8GqD6ef4iQ0UiN5o/S9iJqHnrAe8raE0fm71o9b8wVAaq034T9H2D+Mr4mdnPA6xePxn+e+NZD+T8VFqA/v4z2u0x1JgMDpfyQtVpVRS16n0TI+GYodozvqE0eY5JNY4m+eQlmL24th8bB5/8Dehjt5DYq092vXK2PEqPZuSU8ymXB9Fm1EMDw9/vnvw0cM9g4+CWOve99gVRJ6ie99jV/QMPvo3UdoHHz3X3v60YBV/3iLVd+DhZN8BEGt2l18q9mpCPiDZd0Cfi36T1bExL1TTxQKJjSYHn46mhkCoMUYzKBTyF+VyuWAN7UzD/4MfXBZNDb4kRj9FMyCXy55VKBQfnj+J6gtEywOxXgh29E1oNGcGmUwKtbWSMxLJrnIiD9HQHJUI0Y5Nb+BAKq0FiaTmRE1NzYRPiuQl5HLZfpu7GfztqQlt+87dsGbNA7Br1/eguvrho9XV1V8k8guz5HL5k87GgCD9G7Zsh3Xr1vL0P/zwzkOVlZXn65jzEjKZbJ5CIYd6pRIcvjC4WzrGtV0yNdx009dgw4aHoKrq32DHjm0skUdQKBTLkP46pQqcTdEJ9O94uAbmzLkJtmzZPEr/1hoin1FXJ1PK5TJYu3YNPPjQFrD7wsDa/bBuYyXU1uuhjjTC179+I9x113dg27atUFm5WfSL1TMBuVzuRJVaVlYCW3ZU8/QbLA1QsWELKHQs7FZoefqXLbs3S39+/x2p2toar0RSwxN8zbVXAW1pgModO+GWW74BN8+9BTQGG9x88xxYtGghbN68CXfKq0QeQSKpSdbU7IalS5fAV796HXDOZli3YTNP8+0LFoJcTfP/XrJkMU//xo0bniHyGbt2VbPV1Q/DypUr4MorvwR33bsM6kkjLFp8B6yu2Ag7qiUwd+7NcOedS+Chh9bDunUVfyDyCLt2Vbci/ffeezdP/8rSVSBVUrBg4SLefmzcWgVz594Cd9+9lKe/oqJC8EWjvMHOnTuLULfi6rn22qth9uyr4Z5l98P3pErYvH0n3PrNW+HWW+fxAlu79kE08O1EHqGqqmor0o/Mvuaar/D0rygugV0yFWzYsgPmz5/H019SUszTv3r16vyufMegb+vWrS9u3VoJK1bcD9dffx3ccMNXYc6cG3m1NX/+XFi69E544IFV2NIlJSV55c/X1Kz/3LZtW/+C9N933z1w/fWz4cYbkf6bRumfx++O1asfgFWryk8XFxffQOQ7Nm/efN/GjRvOoCdVWloKixcvgttv/xb/3+XLl0F5eRmUlZVCSUlJA5GH2LRpU8XGjRvSSH9JSRFv72677Zvw7W/fAcuX34+CGKW/iCQ+LKioqCivqFh79MEH18CaNav5HfGeIIrTRUUrG8Te58sHVFRUVFZUrH0X6cfdkKW/tLQEiouLzhUVrTDMeKr9UlFeXv7FVavKuNLSkidKS0teLS5e+ccVK1aEli9fnldqSgxlZWVXr1pVZi8rK/0N0l9UtPL3K1cubykqum+u6EUFFFAAMfP4fzjGt7S4MIzUAAAAAElFTkSuQmCC">

    </div>

    <a href="categorias" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-2 col-xs-6 text-uppercase">

  <div class="small-box bg-black">

    <div class="inner">

      <h3><?php echo number_format($totalMeseros); ?></h3>

      <p>Meseros</p>

    </div>

    <div class="icon">

     <img src="data:image/png;base64,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">

    </div>

    <a href="meseros" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>


<div class="col-lg-2 col-xs-6 text-uppercase">

  <div class="small-box bg-blue">

    <div class="inner">

      <h3><?php echo number_format($totalusuarios); ?></h3>

      <p>USUARIOS</p>

    </div>

    <div class="icon">

     
    <i class="ion ion-person-add"></i>

    </div>

    <a href="usuarios" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-2 col-xs-6 text-uppercase">

  <div class="small-box bg-cafe">

    <div class="inner">

    <h3><?php echo number_format($totalproveedor); ?></h3>

      <p>PROVEEDOR</p>

    </div>

    <div class="icon">

     
    <i class="ion ion-clipboard"></i>

    </div>

    <a href="proveedor" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-3 col-xs-6 text-uppercase">

<div class="small-box bg-yellow">

    <div class="inner">

      <h3><?php echo number_format($totalcliente); ?></h3>

      <p>CLIENTES</p>

    </div>

    <div class="icon">

<img src="data:image/png;base64,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" alt="budget">

    </div>

    <a href="clientes" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>