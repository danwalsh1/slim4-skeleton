<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder)
{
    // Global settings object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function ()
        {
            $sentry_env = getenv('SENTRY_ENV');
            $sentry_env = (is_string($sentry_env) && trim($sentry_env) !== '') ? $sentry_env : 'production';

            return new Settings([
                'debug' => getenv('DEBUG') === '1',
                'displayErrorDetails' => getenv('DISPLAY_ERRORS') === '1',
                'environment_name' => getenv('ENVIRONMENT_NAME'),
                'sendgrid_api_key' => getenv('SENDGRID_API_KEY'),
                'tiny_mce_editor_key' => getenv('TINY_EDITOR_KEY'),
                'logger' =>[
                    'log_file' => getenv('LOG_FILE'),
                    'name' => getenv('LOGGER_NAME'),
                    'debug' => getenv('DEBUG') === '1',
                    'papertrail_target' => getenv('PAPERTRAIL_TARGET')
                ],
                'guzzle' => [
                    'verify' => true
                ],
                'google_recaptcha' => [
                    'site_key' => getenv('GOOGLE_RECAPTCHA_SITE_KEY'),
                    'secret_key' => getenv('GOOGLE_RECAPTCHA_SECRET_KEY')
                ],
                'monitoring' => [
                    'sentry_dsn' => getenv('SENTRY_DSN'),
                    'sentry_env' => $sentry_env,
                    'sentry_release' => 'SENTRY_RELEASE_VALUE'
                ]
            ]);
        }
    ]);
};