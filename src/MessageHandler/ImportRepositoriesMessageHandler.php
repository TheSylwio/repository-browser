<?php

namespace App\MessageHandler;

use App\Message\ImportRepositoriesMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ImportRepositoriesMessageHandler implements MessageHandlerInterface {
	public function __invoke(ImportRepositoriesMessage $message) {
		echo sprintf('Requested import %s repositories from %s organisation', $message->getProvider(), $message->getOrganisation());
	}
}
