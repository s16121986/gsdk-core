<?php

namespace Gsdk\Core\Entities;

use Gsdk\Core\Services\IniReader;

class IniConfig {

	private array $data = [];

	public function __construct(string|array $data = null) {
		if (null === $data)
			return;

		if (is_string($data))
			$this->fromFile($data);
		else
			$this->setData($data);
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	public function __get($name) {
		return $this->get($name);
	}

	public function setData(array $data): static {
		foreach ($data as $k => $v) {
			$this->set($k, $v);
		}
		return $this;
	}

	public function set(string $name, $value): static {
		$this->data[$name] = $value;
		return $this;
	}

	public function get(string $name, $default = null) {
		$value = $this->data;
		foreach (explode('.', $name) as $k) {
			if (!isset($value[$k]))
				return $default;

			$value = $value[$k];
		}

		return $value;
	}

	public function has(string $name): bool {
		$value = $this->data;
		foreach (explode('.', $name) as $k) {
			if (!isset($value[$k]))
				return false;

			$value = $value[$k];
		}

		return true;
	}

	public function toArray(): array {
		return $this->data;
	}

	public function isEmpty(): bool {
		return empty($this->data);
	}

	public function fromFile(string $filename): static {
		$data = (new IniReader($filename))->parse();

		return $this->setData($data);
	}

}