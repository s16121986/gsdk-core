<?php

namespace Gsdk\Core\Services;

class NamespaceReader {

	private string $namespacePath;

	private string $namespace;

	public function __construct(string $namespace) {
		$this->namespace = $namespace;

		$appNamespace = app()->getNamespace();
		if (!str_starts_with($namespace, $appNamespace))
			throw new \Exception('Namespace out of app namespaces');

		$ns = substr($namespace, strlen($appNamespace));
		$this->namespacePath = app_path(str_replace('\\', DIRECTORY_SEPARATOR, $ns));
	}

	public function read(): array {
		$classes = [];
		foreach (static::scanDir($this->namespacePath, '') as $item) {
			$cls = $this->namespace . str_replace(DIRECTORY_SEPARATOR, '\\', $item);
			$classes[] = $cls;
		}

		return $classes;
	}

	private static function scanDir($basePath, $subPath): array {
		$scanPath = $basePath . $subPath;
		$handle = opendir($scanPath);
		if (!$handle)
			return [];

		$list = [];
		while (false !== ($entry = readdir($handle))) {
			if (in_array($entry, ['.', '..']))
				continue;

			$sub = $subPath . DIRECTORY_SEPARATOR . $entry;

			if (is_dir($basePath . $sub)) {
				foreach (static::scanDir($basePath, $sub) as $cls) {
					$list[] = $cls;
				}
			} else
				$list[] = substr($sub, 0, -4);
		}

		closedir($handle);

		return $list;
	}

}
