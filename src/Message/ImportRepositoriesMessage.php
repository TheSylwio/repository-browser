<?php

namespace App\Message;

final class ImportRepositoriesMessage {
	private string $organisation;

	private string $provider;

	public function __construct(string $organisation, string $provider) {
		$this->organisation = $organisation;
		$this->provider = $provider;
	}

	public function getOrganisation(): string {
		return $this->organisation;
	}

	public function getProvider(): string {
		return $this->provider;
	}
}
