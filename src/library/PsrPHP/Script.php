<?php

declare(strict_types=1);

namespace App\Psrphp\Web\PsrPHP;

use PDO;

class Script
{
    public static function onInstall()
    {
        $sql = self::getInstallSql();
        self::execSql($sql);
    }

    public static function onUninstall()
    {
        $sql = '';
        fwrite(STDOUT, "是否删除数据库？y [y,n]：");
        switch (trim((string) fgets(STDIN))) {
            case '':
            case 'y':
            case 'yes':
                fwrite(STDOUT, "删除数据库\n");
                $sql .= PHP_EOL . self::getUninstallSql();
                break;
            default:
                break;
        }
        self::execSql($sql);
    }

    private static function execSql(string $sql)
    {
        $sqls = array_filter(explode(";" . PHP_EOL, $sql));

        $prefix = 'prefix_';
        $cfg_file = getcwd() . '/config/database.php';
        $cfg = (array)include $cfg_file;
        if (isset($cfg['master']['prefix'])) {
            $prefix = $cfg['master']['prefix'];
        }

        $dbh = new PDO("{$cfg['master']['database_type']}:host={$cfg['master']['server']};dbname={$cfg['master']['database_name']}", $cfg['master']['username'], $cfg['master']['password']);

        foreach ($sqls as $sql) {
            $dbh->exec(str_replace('prefix_', $prefix, $sql . ';'));
        }
    }

    private static function getInstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_web_page`;
CREATE TABLE `prefix_psrphp_web_page` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `page` varchar(255) NOT NULL DEFAULT '' COMMENT '页面',
    `tips` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    `tpl` text COMMENT '模板',
    `state` tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
str;
    }

    private static function getUninstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_web_page`;
str;
    }
}
