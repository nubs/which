<?php
namespace Nubs\Which\Application;

use Nubs\Which\Locator;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;

class WhichApplication extends SymfonyApplication
{
    /** @type \Nubs\Which\Locator The application's locator. */
    protected $_locator;

    /**
     * Initialize the application.
     *
     * @param \Nubs\Which\Locator $locator The locator to use.
     */
    public function __construct(Locator $locator)
    {
        parent::__construct('Which', '1.0.0');

        $this->_locator = $locator;
    }

    /**
     * Fetch the locator.
     *
     * @return \Nubs\Which\Locator The locator to use for the application.
     */
    public function getLocator()
    {
        return $this->_locator;
    }

    /**
     * Gets the name of the command based on input.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input The input
     *     interface.
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'which';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand which is used
        // when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new WhichCommand();

        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command name to be
     * the first argument.
     *
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();

        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
