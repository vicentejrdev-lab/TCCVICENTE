    public function requiresIndependentApproval(): bool
    {
        $value = SystemSetting::query()
            ->where('setting_key', self::SETTING_KEY)
            ->value('setting_value');

        if (is_string($value)) {
            $value = json_decode($value, true, flags: JSON_THROW_ON_ERROR);
        }

        return (bool) data_get($value, 'enabled', true);
    }
