<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Doctrine\ORM\EntityManagerInterface;

require_once __DIR__.'/vendor/autoload_runtime.php';

return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    $kernel->boot();
    $container = $kernel->getContainer();
    $conn = $container->get('doctrine.dbal.default_connection');

    $tables = [
        'tbl_balance',
        'tbl_exc_comm',
        'tbl_fixed_asset_loan',
        'tbl_form_fee', // Added for completeness, though manually cleaning above
        'tbl_layya',
        'tbl_monthly_deduction',
        'tbl_outstanding',
        'tbl_share',
        'tbl_soft_loan',
        'tbl_total_savings',
        'tbl_upgrade',
        'tbl_upgrade_tmp',
        'tbl_watanda',
        'tbl_withdrowal'
    ];

    echo "Cleaning up parent orphan records...\n";
    foreach ($tables as $table) {
        $count = $conn->executeQuery("SELECT COUNT(*) FROM $table WHERE memberId NOT IN (SELECT id FROM tbl_users)")->fetchOne();
        if ($count > 0) {
            echo "Found $count orphans in $table. Deleting...\n";
            $conn->executeStatement("DELETE FROM $table WHERE memberId NOT IN (SELECT id FROM tbl_users)");
            echo "Deleted.\n";
        } else {
            echo "No orphans in $table.\n";
        }
    }
    
    // Check permissions
    $count = $conn->executeQuery("SELECT COUNT(*) FROM tbl_permissions WHERE userId NOT IN (SELECT id FROM admin)")->fetchOne();
    if ($count > 0) {
         echo "Found $count orphans in tbl_permissions (userId). Deleting...\n";
         $conn->executeStatement("DELETE FROM tbl_permissions WHERE userId NOT IN (SELECT id FROM admin)");
    }

    $count = $conn->executeQuery("SELECT COUNT(*) FROM tbl_permissions WHERE linkId NOT IN (SELECT id FROM tbl_access_links)")->fetchOne();
    if ($count > 0) {
         echo "Found $count orphans in tbl_permissions (linkId). Deleting...\n";
         $conn->executeStatement("DELETE FROM tbl_permissions WHERE linkId NOT IN (SELECT id FROM tbl_access_links)");
    }

    echo "Cleanup complete.\n";
};
