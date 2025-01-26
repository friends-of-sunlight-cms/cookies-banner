<?php

use Sunlight\Database\Database as DB;
use Sunlight\Plugin\PluginInstaller;

return new class extends PluginInstaller {

  protected function doInstall(): void {
    $this->loadSqlDump(__DIR__ . '/install.sql');
  }

  protected function doUninstall(): void {
    $this->loadSqlDump(__DIR__ . '/uninstall.sql');
  }

  protected function verify(): bool {
    $missingTables = $this->checkTables([
      DB::table('cookies_scripts'),
      DB::table('cookies_settings')
    ]);

    return (count($missingTables) == 0);
  }
};