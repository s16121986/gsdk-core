<?php
/**
 * Класс перечислений
 */

namespace Gsdk\Core;

use ReflectionClass;

abstract class Enum {

	protected static $constants;

	/**
	 * Получить список констант класса
	 *
	 * @return array
	 */
	public static function constants(): array {
		return (new ReflectionClass(get_called_class()))->getConstants();
	}

	/**
	 * Получить значения констант
	 *
	 * @return array
	 */
	public static function values(): array {
		return array_values(static::constants());
	}

	/**
	 * Получить имена констант
	 *
	 * @return array
	 */
	public static function keys(): array {
		return array_keys(static::constants());
	}

	/**
	 * Получить локализованные имена констант
	 *
	 * @return array
	 */
	public static function labels(): array {
		$cls = static::getName();
		$array = [];
		foreach (static::constants() as $k => $v) {
			$array[$v] = lang($cls . '::' . $k);
		}
		return $array;
	}

	/**
	 * Проверить наличие константы по значению
	 *
	 * @param $value Значение константы
	 * @return bool
	 */
	public static function valueExists($value): bool {
		return in_array($value, static::constants());
	}

	/**
	 * Проверить наличие константы по наименованию
	 *
	 * @param string $key Имя константы
	 * @return bool
	 */
	public static function keyExists(string $key): bool {
		return array_key_exists($key, static::constants());
	}

	/**
	 * Получить значение константы
	 *
	 * @param $key Имя константы
	 * @return mixed
	 */
	public static function getValue($key) {
		//$key = strtoupper($key);
		foreach (static::constants() as $k => $v) {
			if ($k == $key)
				return $v;
		}
		return null;
	}

	/**
	 * Получить имя константы по значению
	 *
	 * @param $value Значение константы
	 * @return string
	 */
	public static function getKey($value) {
		foreach (static::constants() as $k => $v) {
			if ($v == $value)
				return $k;
		}
		return null;
	}

	/**
	 * Получить полное наименование константы, включая имя класса
	 *
	 * @param $value Значение константы
	 * @return string
	 */
	public static function getName($value = null): string {
		return get_called_class() . (null === $value ? '' : '::' . static::getKey($value));
	}

	/**
	 * Получить локализованное имя константы
	 *
	 * @param $val Значение константы
	 * @return string
	 */
	public static function getLabel($val): string {
		$const = static::constants();
		return (in_array($val, $const) ? lang(static::getName() . '::' . array_search($val, $const)) : '');
	}

	/**
	 * Получить константу по умолчанию (первую по счету)
	 *
	 * @return mixed
	 */
	public static function default() {
		$const = static::constants();
		return array_shift($const);
	}

	/**
	 * Получить константы в виде массива
	 *
	 * @param bool $valueIndex (ключами выступают: true - значения, false - имена констант)
	 * @return array
	 */
	public static function asArray($valueIndex = false): array {
		$array = [];
		foreach (static::constants() as $k => $v) {
			$array[$v] = $k;
		}
		return $array;
	}

	public static function getHtml($val): string {
		$cls = static::getName();
		$const = static::constants();
		return '<span class="' . $cls . ' ' . static::getKey($val) . '">' . (in_array($val, $const) ? lang($cls . '::' . array_search($val, $const)) : '') . '</span>';
	}

}
