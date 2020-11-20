<?php

namespace Cabag\BackendProgress\Progress;

use Cabag\BackendProgress\Progress\ProgressInterface;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait BackendProgressAwareTrait
{
    /**
     * Unique identifier
     * @var string
     */
    private $identifier = 'base_trait_identifier';

    /**
     * Get the registry
     * @return Registry
     */
    protected function getRegistry(): Registry
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return GeneralUtility::makeInstance(Registry::class);
    }

    /**
     * Register the task as running
     *
     * @param string $identifier Task identifier
     * @param int $steps Amount of steps in the current task
     * @param string $type
     * @param int $maxTtl Maximum time to live for a single task
     * @return void
     */
    protected function registerTaskInProgress(string $identifier, int $steps, string $type = ProgressInterface::TYPE_PROGRESS_BAR, int $maxTtl = 86400): void
    {
        $this->identifier = sprintf('%s_%f', $identifier, microtime(true));

        $this->setData(
            [
                'currentStep' => null,
                'steps' => $steps,
                'type' => $type,
                'ttl' => time() + $maxTtl
            ]
        );
    }

    /**
     * Update progress status
     *
     * @param array $update
     * @return void
     */
    protected function updateTaskProgress(array $update): void
    {
        $data = $this->getData();

        ArrayUtility::mergeRecursiveWithOverrule($data, $update);

        $this->setData($data);
    }

    /**
     * Clean up after the task was run.
     * @param int $seconds Set the amount of time the finished task should still appear in the UI.
     * @return void
     */
    protected function cleanupTask(int $seconds = 60): void
    {
        $this->updateTaskProgress(['ttl' => time() + abs($seconds)]);
    }

    /**
     * Start the current task
     * @param string $label
     */
    protected function startTask($label = '')
    {
        $this->updateTaskProgress(['currentStep' => 0, 'label' => $label]);
    }

    /**
     * Go to next step
     * @param string $label
     */
    protected function nextStep($label = '')
    {
        $data = $this->getData();
        // In case someone calls next step without starting the task first
        $currentStep = $data['currentStep'] ?? 0;

        $data['currentStep'] = $currentStep + 1;
        $data['label'] = $label;

        // Ensure there is always a step left as long as we didn't end the task
        if ($data['currentStep'] >= $data['steps']) {
            $data['steps']++;
        }

        $this->setData($data);
    }

    /**
     * Update the label of the current step
     *
     * @param string $label
     */
    private function updateStepLabel(string $label)
    {
        $data = $this->getData();

        $data['label'] = $label;

        $this->setData($data);
    }

    /**
     * Get the already saved data
     */
    protected function getData(): array
    {
        $data = $this->getRegistry()->get(
            'backend_progress',
            $this->identifier
        ) ?? [];
        // If for some reason, get data is called before it was initialized
        if(empty($data)) {
            $this->registerTaskInProgress(strtolower(__CLASS__) . '_anonymous_task', 1);
            $data = $this->getRegistry()->get(
                'backend_progress',
                $this->identifier
            );
        }

        return $data;
    }

    /**
     * Set the new data
     */
    protected function setData(array $data): void
    {
        $this->getRegistry()->set(
            'backend_progress',
            $this->identifier,
            $data
        );
    }

    /**
     * Ent the current task
     */
    protected function endTask()
    {
        $data = $this->getData();

        ArrayUtility::mergeRecursiveWithOverrule($data, ['currentStep' => $data['steps']]);

        $this->setData($data);
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->cleanupTask(30);
    }
}
