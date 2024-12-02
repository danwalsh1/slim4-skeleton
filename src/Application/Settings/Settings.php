<?php

declare(strict_types=1);

namespace App\Application\Settings;

use App\Application\Settings\SettingsInterface;

class Settings implements SettingsInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $settings;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key = ''): mixed
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}
