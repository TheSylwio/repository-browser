<?php

namespace App\MessageHandler;

use App\Message\ImportRepositoriesMessage;
use App\Service\GitHubService;
use App\Service\GitService;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class ImportRepositoriesMessageHandler implements MessageHandlerInterface {
	private GitHubService $githubService;
	private GitService $gitService;

	public function __construct(GitHubService $githubService, GitService $gitService) {
		$this->githubService = $githubService;
		$this->gitService = $gitService;
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws Exception
	 */
	public function __invoke(ImportRepositoriesMessage $message) {
		switch ($message->getProvider()) {
			case 'GitHub':
				$repositories = $this->githubService->fetchRepositories($message->getOrganisation());
				$this->gitService->saveRepositories($repositories);
				break;
			default:
				throw new Exception('Passed incorrect git provider');
		}
	}
}
