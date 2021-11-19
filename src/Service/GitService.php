<?php

namespace App\Service;

use App\Entity\GitProvider;
use App\Entity\GitRepository;
use Doctrine\ORM\EntityManagerInterface;

class GitService {
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	/**
	 * @param GitRepository[] $repositories
	 * @param GitProvider $provider
	 */
	public function saveRepositories(array $repositories, GitProvider $provider) {
		foreach ($repositories as $repository) {
			$repository->setProvider($provider);
			$this->em->persist($repository);
		}

		$this->em->flush();
	}

	public function getProviderEntity(string $organisation, string $provider) {
		$repo = $this->em->getRepository(GitProvider::class);
		$providerEntity = $repo->findOneBy(['organisation' => $organisation, 'name' => $provider]);

		if (!$providerEntity) {
			$providerEntity = new GitProvider();
			$providerEntity
				->setName($provider)
				->setOrganisation($organisation);
			$this->em->persist($providerEntity);
			$this->em->flush();
		}

		return $providerEntity;
	}
}