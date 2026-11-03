<?php
/**
 * Versión de assets estáticos (CSS/JS).
 * Al publicar cambios de frontend, subí este valor para forzar recarga
 * sin que el usuario limpie caché (Ctrl+F5).
 */
if (!defined("APP_ASSET_VERSION")) {
	define("APP_ASSET_VERSION", "2026.11.03.1");
}

if (!function_exists("assetUrl")) {
	/**
	 * Agrega ?v=APP_ASSET_VERSION a una ruta local de CSS/JS.
	 * No modifica URLs absolutas (http/https).
	 */
	function assetUrl($path)
	{
		$path = (string) $path;
		if ($path === "" || preg_match('#^(https?:)?//#i', $path)) {
			return $path;
		}
		$sep = (strpos($path, "?") !== false) ? "&" : "?";
		return $path . $sep . "v=" . rawurlencode(APP_ASSET_VERSION);
	}
}
