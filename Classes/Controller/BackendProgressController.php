<?php

namespace Cabag\BackendProgress\Controller;

use Doctrine\DBAL\Driver\Statement;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendProgressController
{
    public function getProgress(ServerRequestInterface $request): Response
    {

        $result = $this->getAllRegistryEntries();

        $data = ['response' => 200];
        $entriesToDelete = [];
        while ($row = $result->fetch()) {
            $data[$row['uid']] = unserialize($row['entry_values']);
            if ($data[$row['uid']]['ttl'] < time()) {
                $entriesToDelete[] = $row['uid'];
            }
        }

        $this->cleanUpFinishedTasks($entriesToDelete);
        return new JsonResponse($data);
    }

    /**
     * Clean up finished tasks.
     *
     * This must not be executed on each call, therefore we only do this once every 100 times.
     *
     * @return void
     */
    protected function cleanupFinishedTasks(array $entriesToDelete): void
    {
        mt_srand(time());

        if (mt_rand(1, 100) === 100) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class);
            $queryBuilder
                ->getQueryBuilderForTable('sys_registry')
                ->delete('sys_registry')
                ->where($queryBuilder->expr()->in('uid', $entriesToDelete))
                ->execute();
        }
    }

    /**
     *
     */
    protected function getAllRegistryEntries(): Statement
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_registry')
            ->select(
                ['uid', 'entry_value'],
                'sys_registry',
                ['entry_namespace' => 'backend_progress']
            );
    }
}
