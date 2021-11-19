<?php

namespace App\Entity;

use App\Entity\Interfaces\GitProviderInterface;

class GitProvider implements GitProviderInterface {
	private string $organisation;
	private array $repositories;
	private string $provider;

	public function __construct(string $organisation) {
		$this->organisation = $organisation;
	}

	public function getOrganisation(): string {
		return $this->organisation;
	}

	public function setOrganisation(string $organisation): void {
		$this->organisation = $organisation;
	}

	public function getRepositories(): array {
		return $this->repositories;
	}

	public function setRepositories(array $repositories): void {
		$this->repositories = $repositories;
	}

	public function getProvider(): string {
		return $this->provider;
	}

	public function setProvider(string $provider): void {
		$this->provider = $provider;
	}
}