<?php
namespace Nubs\Which\Application;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WhichCommand extends SymfonyCommand
{
    /**
     * Configures the command's options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('which')
            ->setDescription('Locate a command in the PATH environment')
            ->addArgument('commands', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The commands to locate');
    }

    /**
     * Locates the commands given as arguments.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input The command
     *     input.
     * @param \Symfony\Component\Console\Output\OutputInterface $output The
     *     command output.
     * @return int The exit status of the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $locator = $this->getApplication()->getLocator();
        $exitStatus = 0;

        foreach ($input->getArgument('commands') as $command) {
            $location = $locator->locate($command);

            if ($location === null) {
                $output->writeln("<error>{$command} not found</error>");
                $exitStatus = 1;
            } else {
                $output->writeln($location);
            }
        }

        return $exitStatus;
    }
}
