<?php

namespace App\Entity;

class GitRepository {
	private int $id;
	private string $name;
	private int $pullRequestsCount;
	private int $commitCount;
	private int $starsCount;

	public function getId(): int {
		return $this->id;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): static {
		$this->name = $name;
		return $this;
	}

	public function getPullRequestsCount(): int {
		return $this->pullRequestsCount;
	}

	public function setPullRequestsCount(int $pullRequestsCount): static {
		$this->pullRequestsCount = $pullRequestsCount;
		return $this;
	}

	public function getCommitCount(): int {
		return $this->commitCount;
	}

	public function setCommitCount(int $commitCount): static {
		$this->commitCount = $commitCount;
		return $this;
	}

	public function getStarsCount(): int {
		return $this->starsCount;
	}

	public function setStarsCount(int $starsCount): static {
		$this->starsCount = $starsCount;
		return $this;
	}
}