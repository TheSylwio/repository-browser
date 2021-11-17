<?php

namespace App\Service;

use App\Entity\GitRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubService {
	private HttpClientInterface $client;

	private static int $itemsPerPage = 100;

	public function __construct(HttpClientInterface $client) {
		$this->client = $client->withOptions([
			'base_uri' => 'https://api.github.com',
			'headers' => [
				'User-Agent' => 'request',
			]
		]);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws Exception
	 * @throws DecodingExceptionInterface
	 */
	public function fetchRepositories(string $organisation) {
		$this->checkOrganisation($organisation);

		$repositories = [];
		$page = 0;

		do {
			$response = $this->client->request('GET', sprintf('/orgs/%s/repos?per_page=%s&page=%s', $organisation, self::$itemsPerPage, ++$page));
			$fetchedRepositories = $response->toArray();
			$repositories = array_merge($fetchedRepositories, $repositories);
		} while (count($fetchedRepositories) === self::$itemsPerPage);

		$repositories = array_map(function ($repository) {
			return (new GitRepository())
				->setName($repository['name'])
				->setCommitCount($this->getRepositoryCommitCount($repository))
				->setPullRequestsCount(0)
				->setStarsCount($repository['stargazers_count']);
		}, $repositories);
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws Exception
	 */
	private function checkOrganisation(string $organisation): void {
		$response = $this->client->request('HEAD', sprintf('/orgs/%s', $organisation));
		if ($response->getStatusCode() === Response::HTTP_NOT_FOUND) {
			throw new Exception(sprintf('Organisation %s not found.', $organisation));
		}
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws ClientExceptionInterface
	 */
	private function getRepositoryCommitCount(array $repository) {
		$page = 0;
		$commitCount = 0;

		do {
			$response = $this->client->request('GET', sprintf('/repos/%s/contributors?per_page=%s&page=%s', $repository['full_name'], self::$itemsPerPage, ++$page));
			$contributors = $response->toArray();
			$commitCount += array_reduce($contributors, function ($acc, $item) {
				return $acc + $item['contributions'];
			});
		} while (count($contributors) === self::$itemsPerPage);

		return $commitCount;
	}
}