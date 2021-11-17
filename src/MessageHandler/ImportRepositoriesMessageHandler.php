<?php

namespace App\MessageHandler;

use App\Message\ImportRepositoriesMessage;
use App\Service\GitHubService;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class ImportRepositoriesMessageHandler implements MessageHandlerInterface {
	private GitHubService $githubService;

	public function __construct(GitHubService $githubService) {
		$this->githubService = $githubService;
	}

	/**
	 * @throws TransportExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws Exception
	 */
	public function __invoke(ImportRepositoriesMessage $message) {
		switch ($message->getProvider()) {
			case 'GitHub':
				$this->githubService->fetchRepositories($message->getOrganisation());
				break;
			default:
				throw new Exception('Passed incorrect git provider');
		}
	}
}
