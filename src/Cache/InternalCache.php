<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * Internal PSR-16 cache implementation 
 */
class InternalCache implements CacheInterface
{
    /**
     * Internal simple container for cache items
     *
     * @var array
     */
    protected array $container = [];

    /**
     * {@inheritDoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return match ($this->has($key)) {
            true => $this->container[$key],
            default => $default,
        };
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key): bool
    {
        return !empty($this->container[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        $this->container[$key] = $value;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $key): bool
    {
        unset($this->container[$key]);
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): bool
    {
        $this->container = [];
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $this->get($key, $default);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        $set = true;
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $set = false;
            }
        }
        return $set;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMultiple(iterable $keys): bool
    {
        $delete = true;
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $delete = false;
            }
        }
        return $delete;
    }

}