<?php

namespace Cabag\BackendProgress\Examples\Command;

use Cabag\BackendProgress\Progress\BackendProgressAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleProgressBarCommand extends Command
{
    use BackendProgressAwareTrait;

    /**
     * Configure command
     */
    public function configure()
    {
        $this->registerTaskInProgress('your_awesome_task', 3);
        parent::configure(); // TODO: Change the autogenerated stub
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Start the task
        $this->startTask();
        sleep(1);

        // Go to the next step
        $this->nextStep();
        sleep(1);

        // Update the step label
        $this->updateStepLabel('Executing part one of this step');

        $array = [];
        // Do some more for find $array
        // ...
        $array = range(1, 100);

        // Update in a loop
        $amount = count($array);
        mt_srand(time());
        foreach ($array as $key => $value) {
            usleep(mt_rand(10000, 200000));
            $this->updateStepLabel(sprintf('Update part %s from %d', $key, $amount));
            // Do some processing
        }
        sleep(1);
        // Go to the next step and update label
        $this->nextStep('Label for whole task (can be updated)');
        sleep(2);

        $this->nextStep('This is the last step');
        sleep(1);

        // If you execute nextStep too often, the total amount of steps will raise and the completion will stay at
        // steps-1 until endTask is called
        $this->nextStep('An additional step(what happened here?)');
        sleep(2);

        // End the task
        $this->endTask();

        // Set up how long a task should stay in the queue after finishing (default 60 seconds)
        $this->cleanupTask(10);
        // Negative values are put into absolute values, so this is equivalent
        $this->cleanupTask(-10);
    }
}
