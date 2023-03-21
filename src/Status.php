<?php

namespace Funnelchat\Status;

use Laravel\Nova\Card;

class Status extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'status';
    }

    public function setData($supplier, $device_alias, $device_number, $device_id, $device_color, $instance_code, $token, $official_status = null, $admin_view = false)
    {
        return $this->withMeta([
            'supplier' => $supplier,
            'device_alias' => $device_alias,
            'device_color' => $device_color,
            'device_number' => $device_number,
            'device_id' => $device_id,
            'instance_code' => $instance_code,
            'token' => $token,
            'official_status' => $official_status,
            'admin_view' => $admin_view,
            'wapi_url' => env("APP_WAPI_URL"),
            'webhook_url' => env("APP_WEBHOOK_URL")
        ]);
    }
}
