<?php

namespace App\Services\Devices\Contracts;

interface DeviceDriverInterface
{
    /**
     * Establish a connection to the network hardware.
     */
    public function connect(string $host, int $port, array $credentials): bool;

    /**
     * Run a custom/raw query or command on the device.
     */
    public function runCommand(string $command): string;

    /**
     * Whitelist a user MAC address on the hotspot.
     */
    public function createHotspotUser(string $macAddress, int $durationMinutes, ?string $rateLimit = null): bool;

    /**
     * Forcefully disconnect a client MAC address.
     */
    public function disconnectUser(string $macAddress): bool;

    /**
     * Check if the device is currently reachable.
     */
    public function checkStatus(): bool;
}
