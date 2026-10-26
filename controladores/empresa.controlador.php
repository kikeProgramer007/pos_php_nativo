<?php

require_once __DIR__ . "/../modelos/empresa.modelo.php";

class ControladorEmpresa
{
	static public function ctrGuardar()
	{
		if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["guardarEmpresa"])) {
			return;
		}
		if (!Permisos::tiene("empresa.ver")) {
			return;
		}

		$actual = ModeloEmpresa::mdlActual();
		if (!$actual) {
			echo '<script>
				swal({ type: "error", title: "Falta la empresa", text: "Ejecuta primero el script de tu cliente en la base de datos." })
				.then(function(){ window.location = "empresa"; });
			</script>';
			return;
		}

		$nombre = trim((string) ($_POST["nombre"] ?? ""));
		$direccion = trim((string) ($_POST["direccion"] ?? ""));
		if ($nombre === "") {
			echo '<script>
				swal({ type: "error", title: "El nombre es obligatorio" })
				.then(function(){ window.location = "empresa"; });
			</script>';
			return;
		}

		$logo = $actual["logo"] ?? "images/logo-negro-bloque.jpg";
		if (!empty($_FILES["logo"]["name"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
			$logoNuevo = self::guardarLogo($_FILES["logo"]);
			if ($logoNuevo === "") {
				echo '<script>
					swal({ type: "error", title: "Logo no válido", text: "Usa JPG o PNG de hasta 2 MB." })
					.then(function(){ window.location = "empresa"; });
				</script>';
				return;
			}
			$logo = $logoNuevo;
		}

		$logoDashboard = $actual["logo_dashboard"] ?? "vistas/img/plantilla/Logo_POS.webp";
		if (!empty($_FILES["logo_dashboard"]["name"]) && is_uploaded_file($_FILES["logo_dashboard"]["tmp_name"])) {
			$logoNuevo = self::guardarLogoDashboard($_FILES["logo_dashboard"]);
			if ($logoNuevo === "") {
				echo '<script>
					swal({ type: "error", title: "Logo del tablero no válido", text: "Usa JPG, PNG o WEBP de hasta 2 MB." })
					.then(function(){ window.location = "empresa"; });
				</script>';
				return;
			}
			$logoDashboard = $logoNuevo;
		}

		$notaTicket = str_replace(array("\r\n", "\r"), "\n", trim((string) ($_POST["nota_ticket"] ?? "")));
		if (mb_strlen($notaTicket) > 100) {
			echo '<script>
				swal({ type: "error", title: "La nota del ticket admite 100 caracteres" })
				.then(function(){ window.location = "empresa"; });
			</script>';
			return;
		}

		$respuesta = ModeloEmpresa::mdlGuardar(array(
			"nombre" => $nombre,
			"direccion" => $direccion,
			"logo" => $logo,
			"logo_dashboard" => $logoDashboard,
			"nota_ticket" => $notaTicket
		));
		if ($respuesta === "columnas") {
			echo '<script>
				swal({ type: "warning", title: "Falta el script", text: "Ejecuta sql/gato_rico.sql o sql/pollos365.sql, el que corresponde a esta base." })
				.then(function(){ window.location = "empresa"; });
			</script>';
			return;
		}
		if ($respuesta !== "ok") {
			echo '<script>
				swal({ type: "error", title: "No se pudo guardar" })
				.then(function(){ window.location = "empresa"; });
			</script>';
			return;
		}

		$activas = isset($_POST["funcionalidad"]) ? $_POST["funcionalidad"] : array();
		ModeloEmpresa::mdlGuardarFuncionalidades($activas);

		echo '<script>
			swal({ type: "success", title: "Empresa actualizada" })
			.then(function(){ window.location = "empresa"; });
		</script>';
	}

	static private function guardarLogo($archivo)
	{
		if (intval($archivo["size"]) > 2 * 1024 * 1024) {
			return "";
		}
		$ext = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
		$permitidas = array("jpg", "jpeg", "png");
		if (!in_array($ext, $permitidas, true)) {
			return "";
		}
		$info = @getimagesize($archivo["tmp_name"]);
		if ($info === false) {
			return "";
		}

		$carpeta = __DIR__ . "/../extensiones/tcpdf/pdf/images";
		if (!is_dir($carpeta)) {
			return "";
		}
		$nombre = "empresa-logo." . ($ext === "jpeg" ? "jpg" : $ext);
		$destino = $carpeta . "/" . $nombre;
		if (!move_uploaded_file($archivo["tmp_name"], $destino)) {
			return "";
		}
		return "images/" . $nombre;
	}

	static private function guardarLogoDashboard($archivo)
	{
		if (intval($archivo["size"]) > 2 * 1024 * 1024) {
			return "";
		}
		$ext = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
		$permitidas = array("jpg", "jpeg", "png", "webp");
		if (!in_array($ext, $permitidas, true)) {
			return "";
		}
		$info = @getimagesize($archivo["tmp_name"]);
		if ($info === false) {
			return "";
		}

		$carpeta = __DIR__ . "/../vistas/img/empresa";
		if (!is_dir($carpeta) && !mkdir($carpeta, 0775, true) && !is_dir($carpeta)) {
			return "";
		}
		$nombre = "logo-dashboard." . ($ext === "jpeg" ? "jpg" : $ext);
		$destino = $carpeta . "/" . $nombre;
		if (!move_uploaded_file($archivo["tmp_name"], $destino)) {
			return "";
		}
		return "vistas/img/empresa/" . $nombre;
	}
}
