<?php

namespace Gsdk\Core\Services;

class IniReader {

	private $fullname;

	public function __construct(string $fullname) {
		$this->fullname = $fullname;
	}

	public function parse() {
		if (!is_file($this->fullname))
			return null;

		if (defined('INI_SCANNER_TYPED'))
			$data = parse_ini_file($this->fullname, true, INI_SCANNER_TYPED);
		else
			$data = self::parseTyped(parse_ini_file($this->fullname, true, INI_SCANNER_RAW));

		if ($data)
			return $data;
		else
			return null;
	}

	private static function parseTyped(array $array): array {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$array[$k] = self::parseTyped($v);
			} else if (is_string($v)) {
				$vs = strtolower($v);
				$v = match (true) {
					$vs === 'null' => null,
					$vs === 'on', $vs === 'yes', $vs === 'true' => true,
					$vs === 'off', $vs === 'none', $vs === 'false' => false,
					is_numeric($v) => (float)$v,
				};
				$array[$k] = $v;
			}
		}
		return $array;
	}

}
