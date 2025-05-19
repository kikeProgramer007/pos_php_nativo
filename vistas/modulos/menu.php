<aside class="main-sidebar ">

	<section class="sidebar">

		<ul class="sidebar-menu">

			<?php

			if ($_SESSION["perfil"] == "Administrador") {

				echo '<li class="active">

				<a href="inicio">

					<i class="fa fa-home"></i>
					<span>Inicio</span>

				</a>


				 <li>
	
                     <a href="pagina/">
                        <i class="fa fa-globe"></i>

                              <span style="font-weight: bold;">Pagina Web</span>
                        </a>
	
				</li>

			</li>

			<li>

				<a href="usuarios">

					<i class="fa fa-user"></i>
					<span>Usuarios</span>

				</a>

			</li>';
			}

			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Supervisor") {

				echo '<li>

				<a href="categorias">

					<i class="fa fa-th"></i>
					<span>Categorías</span>

				</a>

			</li>

			<li>

				<a href="productos">

					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAADw0lEQVR4nK2US28bVRTHLwIknqsKiQ/ABiEeEkskvgFixaYgNiyQQCoLFixDglQ1NSEpzaNuqEOgDamhJWlo3rEdJ04745m58/B43g/PjD1+JY3bIBpS+6A7lVFUIhaBIx3d0cz//O7/nqM7CP1PcYtVXxuIX34VaYbfy0takqL4U/8F6JT8e7HziVFU1Er9ml7KUazce1yY6fqnms0mfDMyeQGppndaVp0Mi5VjAz2/7DbqdTg3fiWObC8ctd0yhQva0GERwzBPaobSZ1mGq+nyXkEW9gSRdTiO6Umn008c1laq4R/NZgPOxa/EUb3RFOuNZtP1KnxXwHHcu7qh7IsFBrBAARZo4EUaBCkPgsQAz7P7LEu/8zewErYbjQYMDifGkGqU/ixoNtC8sk8+0iz9gShxESjP5mBp+SYsryxCKrUK2Y00FIo8aHoRVE0BQeBOkho3KLfDShXOjkycR6sZik/OLnV+vZmSstnsC7zAtAlsY3MNlpYWYGtrExiGBow5kCQMNE2BqioQBD7outqmKOqErttt03YgNpy4jgDgsdX12w/eSyYfZ5jbywRG0ZuwtrYSFXMcA5IkgKLIBAC2bYIsy1AquRAEAYiiuKRo9oFVCiA++bOIbjDMM0XNftDTM/wch+n7pFdrqVVgGAp4ngNZlkDTFDBNHVzXBs9zI5hhKFAul0HXtX0Ky34QNmDq+ryF0lThRdP2On2D4y9jIQ+8mIfN3MY/XJVKTgTrpmWZEIYVsG0byNSXU7nW6KWrqyiVzb+lWx6cGRo/SYBkiqRnj7p6NInLarUSrYqiPL+9c7c6+t3UDJpfWX9fs1zoi8U/xzzTKSoCCAI+0tXhLJd9CMOQOOyQSe+09nZHLv20jmaWM5+phgN9A2MDGLOhbhRB14tg29a/wmq1MDqyqqo1MtjtO637Y4lpjGYWM6cj4NmxaY7Lf2hZOnheCQyDrM6RsEajGsHIsyRLHxWD1ontO3fbo4mrJrqxkLlIgF99fSFDrIsS1nzfiyYYBGT1oVIJoFIpQ71ejbJa7bormqRmnaLe3tm9B2MT01U081tqjgB7+0ek6KJ7W0/LcsH3vIdQUlivhxGoVnvojKSuFwPHcZ4iNQuprY8jYGK6haZm59M5GsOXZ4aj3UiQnvAiP6Tr6gE5fhdMbodpmQcFWRwkmq7+2nzqC8crw/jktV009ct84sfp2U7/txO5o35Nosi+KQj4U0HiPsEYv3GUZm5x4/WVzK3fLyfnvOjF8Pezr6BDOx4nkotbL8ViPzz7F3BGt3JrU19iAAAAAElFTkSuQmCC">
					<span>Productos</span>

				</a>

				

			</li>';
			}

			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Supervisor") {

				echo '<li>

				<a href="clientes">
			
					<i class="fa fa-users"></i>

				<span>Clientes</span>
				</a>
				

			</li>';
			}

			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "") {

			echo '
				<li>
					<a href="meseros">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAACM0lEQVR4nO2US4jUQBCGI+LjJKigoKCCKF58sOIDPAjeRPAoghfBg4IXQdCTVy8exJsKngSFLILuznZXdtadhZlUZaCrsiyjggriA1dBRBBR8BHpzWQ2m0nigDmJBX1Id1Nf1d9/xXH++dBojmnkm4DmnNvpLK0cACTngSTqLZR7lUM0yZMFEJKo3m6vrhQCyC+ykFEM1xfdV0GwQlN4EkgmFMqpWnNm5SCd3EoDNMrjwrtohjTJy4y8bz0MD5RCJmhmLRBLDOB3ypc9efc8f3oNoMxmu44Xf6w3zYZSUBRFi1TLbDbGLMkFtMJd1hD5gEQBVoDhXqeowvQ3YGcVBLLTdd3Fyd54i9dplLtAXC9aGnlYU7ipl6jm80ZAua2J39sqvIB3J2f2MWOt+bVHZl+2KBWY/Rr5AhBftEsjn8mtHojvZDR15zvhaz0JiD97gTmUnGnk04D8Mz6T7xp50u7lQvocQvJjnGRLt4CpBVqTfNXER7VvzmrkX929q7mJ02+Q/3BywxrAOqXvLK46BqC8snKXQ0gOF9jwm0I5mEr8zM5MZiZmx9rTW0sBXakulVjxeSph005zIp9G+TCGZvsfAXOaozwo83uqsyl7X6mnywDDE3aOBgLMdYI82bXofUW8A4gvA/GbnDd6OHDSvk58s80m0EF4ZB5shgDlS2aCPedvI/0LAZLrfXKhkFNlaJKRHLk+WUtXB0G+AsiPFPFxIGFArtnJbzQayyuD/A8nJ34D3q7BBZ6gxLoAAAAASUVORK5CYII=">
					<span>Meseros</span>
					</a>
				</li>
				<li>
					<a href="arqueo-de-caja">
						<i class="fa fa-inbox" aria-hidden="true"></i>
						<span>Caja</span>
					</a>
				</li>';
			}

		

			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor" || $_SESSION["perfil"] == "Supervisor") {

				echo '<li>

				<a href="crear-venta">
		        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAACVElEQVR4nKWU3UtTcRzGD3TXVXQXXVR/QUQ33kXt1N53nOecba2t2ZKEDNqhF4TooqBASIzIDBZEXbTh1I2aEIjRtpzaTciSMc3QltLL3nUWOffE79RZW7O55gMPz3m+h/Ph9+XAj6LqEK3QNzUbbXMMf/KrZNLJnGpEzaZW1/DzESwvr5RMOplT9YrvxzYhjJ3kWcdbH9y55ywEQmFIJl3HW52bgi6EcFAIY7cQgEcIIiGE0CSX63fpWEsvaz7lotXcD5Kkkzm1mRxBPHQEsCoEgd/uK38vU7GLda8pActgIvAQw+yglVybTM2fOaLkz5P8ZdZ2WNWyh/qXAFx9HK04HdwxfP/0+Uumz/no29++dbs3r+EsuWNq/f4qmN5k6w6NTRSfxCpOB88skEym4PZ4q0zmgz5/Ucdb71cB+ROnJ95OR7ER8NX4JGg1V2Uyj0xHwZrtk1VALW9JZHO5/wZmMlloOUuiAqbRaLbrOOsKALzPAqPxP17IAal0Bu4BX+XKAz6k0xnyCTSsZZUwSkCZgj3Qfu5iEjUU/7i0YRK1nRVSFT+GVvLmru67WdSQZ+gp1tYKcPV7xSRd0o2uniytYg0loIIx3hz0+ddrAaci0ygWi3gzFRGTdEkuj3ddqTddKwHt7Y6e2My7Wjy8eBlCoVDAyGhATNIljY2/Bnfc/kyEHdXy+4Z8/kszs3No1IFQGIzBtiCXG/dSOs463yF05i9fuY6tuEPozBMWxRhsS8lUuua69YgwCEsEfogvVlyejZgwRKDeaBsuv9q34hZjq/8nOt6Ev57j+40AAAAASUVORK5CYII=">
				<span>Vender</span>
				</a>	
			</li>';

			
			
			}
			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Supervisor" || $_SESSION["perfil"] == "Vendedor") {
				echo '<li>
					<a href="ventas">
						<i class="fa fa-money" aria-hidden="true"></i>
						<span>Ventas Realizadas</span>
					</a>
				</li>';
			}

			if ($_SESSION["perfil"] == "" || $_SESSION["perfil"] == "Supervisor") {

				echo '<li>	
                <a href="arqueo-de-caja">
		        <i class="fa fa-inbox" aria-hidden="true"></i>
				<span>Apertura</span>
				</a>


			</li>';

			}
			


			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Supervisor") {

				echo '<li class="treeview">

				<a href="#">

		     <i class="fa fa-cart-plus"></i>
					
					<span>Compras</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">
					
					<li>

						<a href="compras">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar compra</span>

						</a>

					</li>


                           



					<li>

						<a href="crear-compra">
							
							<i class="fa fa-circle-o"></i>
							<span>Crear compra</span>

						</a>

					</li>';

				



				echo '</ul>

			</li>';
			}


			if ($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Supervisor") {

				echo '<li>

				<a href="proveedor">

					<i class="fa fa-truck"></i>
					<span>Proveedor</span>

				</a>

			</li>';
			}

			if ($_SESSION["perfil"] == "" || $_SESSION["perfil"] == "Vendedor") {

				echo '<li>	
                <a href="arqueo-de-caja">
		        <i class="fa fa-inbox" aria-hidden="true"></i>
				<span>Apertura</span>
				</a>


			</li>';

				echo '<li>
					<a href="reporte-venta">
						<i class="fa fa-calendar"></i>
						<span>Reporte de Ventas por Fecha</span>
					</a>
				</li>';
			
				echo '<li>
					<a href="ver-productos-faltantes">
						<i class="fa fa-exclamation-circle"></i>
						<span>Reporte de Productos Faltantes</span>
					</a>
				</li>';
			
				echo '<li>
					<a href="reportes">
						<i class="fa fa-file-text" aria-hidden="true"></i>
						<span>Reporte de ventas</span>
					</a>
				</li>';
			}
			

			if ($_SESSION["perfil"] == "Administrador"   || $_SESSION["perfil"] == "Supervisor"  ) {
				

				

				echo '<li class="treeview">

				<a href="#">

			
					<i class="fa fa-print" aria-hidden="true"></i>
					<span>Reportes</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">
					<li>
						<a href="reporte-venta">
							 <i class="fa fa-calendar"></i> <!-- Cambiado a calendar -->
							<span>R.De. Venta Por Fecha</span>
						</a>
					</li>
					<li>
						<a href="reporte-top-meseros-ventas">
						<i class="fa fa-star"></i> <!-- Cambiado a star -->
							<span>Meseros.Con mas Vtas</span>
						</a>
					</li>
					<li>
						<a href="reporte-categoria">
							 <i class="fa fa-folder"></i> <!-- Cambiado a folder -->
							<span>Rpt. Categorias</span>
						</a>
					</li>
					<li>
						<a href="ver-productos-faltantes">
							 <i class="fa fa-exclamation-triangle"></i> <!-- Cambiado a exclamation-triangle -->
							<span>Rpt. Product Faltante</span>
						</a>
					</li>

					


					<li>

						<a href="reporte-top-productos">
							
						  <i class="fa fa-trophy"></i> <!-- Cambiado a trophy -->
							<span>Prod. más vendido</span>

						</a>

					</li>';

					

				

				if ($_SESSION["perfil"] == "Administrador") {

					echo '<li>

						<a href="ganancias-ventas">
							  <i class="fa fa-line-chart"></i> <!-- Cambiado a line-chart -->
							<span>Rpt. de Ganancias</span>
						</a>

					</li>';
				}

				
				if ($_SESSION["perfil"] == "Administrador") {

					echo '<li>

					<a href="reporte-compra">
							 <i class="fa fa-circle-o"></i>
							<span>Reporte de compras</span>
						</a>

					</li>';
				}


				if ($_SESSION["perfil"] == "Administrador"  || $_SESSION["perfil"] == "Supervisor") {

					echo '<li>

				<a href="reportes">
							<i class="fa fa-circle-o"></i>
						
							<span>Reporte de ventas</span>
						</a>

					</li>';
				}


			

			

				echo '</ul>

			</li>';
			}

			?>

		</ul>

	</section>

</aside>