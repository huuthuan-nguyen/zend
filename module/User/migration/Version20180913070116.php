<?php declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180913070116 extends AbstractMigration
{
    // Create user table.
    public function getDescription()
    {
        return "Create user table.";
    }

    // create user table
    public function up(Schema $schema) : void
    {
        $userTableSql = <<<SQL
            CREATE TABLE `user` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(128) NOT NULL,
            `full_name` varchar(512) NOT NULL,
            `password` varchar(256) NOT NULL,
            `status` int(11) NOT NULL,
            `date_created` datetime NOT NULL,
            `pwd_reset_token` varchar(32) DEFAULT NULL,
            `pwd_reset_token_creation_date` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email_idx` (`email`)
        )
SQL;
        if (!$schema->hasTable('user'))
            $this->addSql($userTableSql);
    }

    public function down(Schema $schema) : void
    {
        // drop user table
        if ($schema->hasTable('user'))
            $schema->dropTable('user');
    }
}
