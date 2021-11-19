<?php

namespace App\Entity;

use App\Entity\Interfaces\GitProviderInterface;
use Doctrine\ORM\PersistentCollection;

class GitProvider implements GitProviderInterface {
	private int $id;
	private string $organisation;
	private PersistentCollection $repositories;
	private string $name;

	public function getId(): int {
		return $this->id;
	}

	public function getOrganisation(): string {
		return $this->organisation;
	}

	public function setOrganisation(string $organisation): GitProvider {
		$this->organisation = $organisation;
		return $this;
	}

	public function getRepositories(): PersistentCollection {
		return $this->repositories;
	}

	public function setRepositories(PersistentCollection $repositories): GitProvider {
		$this->repositories = $repositories;
		return $this;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): GitProvider {
		$this->name = $name;
		return $this;
	}
}