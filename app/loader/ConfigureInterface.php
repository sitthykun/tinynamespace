<?php
/**
 * Project: procambodia.dev
 * User: MasakoKh or Sitthykun LY
 * Date: 5/31/15
 * Time: 11:48 AM
 */

namespace app\loader;


interface ConfigureInterface
{
    // server name or apache variable name
    const SERVER_ENVIRONMENT_NAME       = 'SERVER_ENV';

    // folder
    const CONFIGURATION_PATH            = 'config';

    // environmental server
    const DEVELOPMENT_ENVIRONMENT       = 'development';
    const PRODUCTION_ENVIRONMENT        = 'production';
    const TEST_ENVIRONMENT              = 'test';
}