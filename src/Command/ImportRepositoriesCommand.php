<?php

namespace App\Command;

use App\Message\ImportRepositoriesMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
	name: 'app:import-repositories',
	description: 'Import organisation\'s repositories',
)]
class ImportRepositoriesCommand extends Command {
	private MessageBusInterface $bus;

	public function __construct(MessageBusInterface $bus) {
		parent::__construct();
		$this->bus = $bus;
	}

	protected function configure(): void {
		$this
			->addArgument('organisation', InputArgument::OPTIONAL, 'Organisation name')
			->addOption('provider', null, InputOption::VALUE_REQUIRED, 'Provider name');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		$io = new SymfonyStyle($input, $output);
		$organisation = $input->getArgument('organisation');
		$provider = $input->getOption('provider');
		$helper = $this->getHelper('question');

		if (!$organisation) {
			$question = new Question('Please enter the name of the organisation (default: octokit):', 'octokit');
			$organisation = $helper->ask($input, $output, $question);
		}

		if (!$provider) {
			$question = new ChoiceQuestion('Please select provider name (default: Github)', ['GitHub', 'GitLab', 'BitBucket'], 0);
			$question->setErrorMessage('Provider value %s is invalid.');
			$provider = $helper->ask($input, $output, $question);
		}

		$io->writeln(sprintf('Chosen organisation: %s', $organisation));
		$io->writeln(sprintf('Chosen provider: %s', $provider));

		$this->bus->dispatch(new ImportRepositoriesMessage($organisation, $provider));

		return Command::SUCCESS;
	}
}
