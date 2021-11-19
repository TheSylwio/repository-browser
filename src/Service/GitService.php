<?php

namespace App\Service;

use App\Entity\GitRepository;
use Doctrine\ORM\EntityManagerInterface;

class GitService {
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	/** @param GitRepository[] $repositories */
	public function saveRepositories(array $repositories) {
		foreach ($repositories as $repository) {
			$this->em->persist($repository);
		}

		$this->em->flush();
	}
}