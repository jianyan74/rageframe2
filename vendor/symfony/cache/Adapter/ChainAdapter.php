<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache\Adapter;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\Cache\PruneableInterface;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Component\Cache\Traits\ContractsTrait;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Chains several adapters together.
 *
 * Cached items are fetched from the first adapter having them in its data store.
 * They are saved and deleted in all adapters at once.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ChainAdapter implements AdapterInterface, CacheInterface, PruneableInterface, ResettableInterface
{
    use ContractsTrait;

    private $adapters = [];
    private $adapterCount;
    private $syncItem;

    /**
     * @param CacheItemPoolInterface[] $adapters        The ordered list of adapters used to fetch cached items
     * @param int                      $defaultLifetime The default lifetime of items propagated from lower adapters to upper ones
     */
    public function __construct(array $adapters, int $defaultLifetime = 0)
    {
        if (!$adapters) {
            throw new InvalidArgumentException('At least one adapter must be specified.');
        }

        foreach ($adapters as $adapter) {
            if (!$adapter instanceof CacheItemPoolInterface) {
                throw new InvalidArgumentException(sprintf('The class "%s" does not implement the "%s" interface.', \get_class($adapter), CacheItemPoolInterface::class));
            }

            if ($adapter instanceof AdapterInterface) {
                $this->adapters[] = $adapter;
            } else {
                $this->adapters[] = new ProxyAdapter($adapter);
            }
        }
        $this->adapterCount = \count($this->adapters);

        $this->syncItem = \Closure::bind(
            function ($sourceItem, $item) use ($defaultLifetime) {
                $item->value = $sourceItem->value;
                $item->expiry = $sourceItem->expiry;
                $item->isHit = $sourceItem->isHit;
                $item->metadata = $sourceItem->metadata;

                $sourceItem->isTaggable = false;
                unset($sourceItem->metadata[CacheItem::METADATA_TAGS]);

                if (0 < $sourceItem->defaultLifetime && $sourceItem->defaultLifetime < $defaultLifetime) {
                    $defaultLifetime = $sourceItem->defaultLifetime;
                }
                if (0 < $defaultLifetime && ($item->defaultLifetime <= 0 || $defaultLifetime < $item->defaultLifetime)) {
                    $item->defaultLifetime = $defaultLifetime;
                }

                return $item;
            },
            null,
            CacheItem::class
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null)
    {
        $lastItem = null;
        $i = 0;
        $wrap = function (CacheItem $item = null) use ($key, $callback, $beta, &$wrap, &$i, &$lastItem, &$metadata) {
            $adapter = $this->adapters[$i];
            if (isset($this->adapters[++$i])) {
                $callback = $wrap;
                $beta = INF === $beta ? INF : 0;
            }
            if ($adapter instanceof CacheInterface) {
                $value = $adapter->get($key, $callback, $beta, $metadata);
            } else {
                $value = $this->doGet($adapter, $key, $callback, $beta, $metadata);
            }
            if (null !== $item) {
                ($this->syncItem)($lastItem = $lastItem ?? $item, $item);
            }

            return $value;
        };

        return $wrap();
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        $syncItem = $this->syncItem;
        $misses = [];

        foreach ($this->adapters as $i => $adapter) {
            $item = $adapter->getItem($key);

            if ($item->isHit()) {
                while (0 <= --$i) {
                    $this->adapters[$i]->save($syncItem($item, $misses[$i]));
                }

                return $item;
            }

            $misses[$i] = $item;
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = [])
    {
        return $this->generateItems($this->adapters[0]->getItems($keys), 0);
    }

    private function generateItems($items, $adapterIndex)
    {
        $missing = [];
        $misses = [];
        $nextAdapterIndex = $adapterIndex + 1;
        $nextAdapter = isset($this->adapters[$nextAdapterIndex]) ? $this->adapters[$nextAdapterIndex] : null;

        foreach ($items as $k => $item) {
            if (!$nextAdapter || $item->isHit()) {
                yield $k => $item;
            } else {
                $missing[] = $k;
                $misses[$k] = $item;
            }
        }

        if ($missing) {
            $syncItem = $this->syncItem;
            $adapter = $this->adapters[$adapterIndex];
            $items = $this->generateItems($nextAdapter->getItems($missing), $nextAdapterIndex);

            foreach ($items as $k => $item) {
                if ($item->isHit()) {
                    $adapter->save($syncItem($item, $misses[$k]));
                }

                yield $k => $item;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key)
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->hasItem($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $cleared = true;
        $i = $this->adapterCount;

        while ($i--) {
            $cleared = $this->adapters[$i]->clear() && $cleared;
        }

        return $cleared;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key)
    {
        $deleted = true;
        $i = $this->adapterCount;

        while ($i--) {
            $deleted = $this->adapters[$i]->deleteItem($key) && $deleted;
        }

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys)
    {
        $deleted = true;
        $i = $this->adapterCount;

        while ($i--) {
            $deleted = $this->adapters[$i]->deleteItems($keys) && $deleted;
        }

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item)
    {
        $saved = true;
        $i = $this->adapterCount;

        while ($i--) {
            $saved = $this->adapters[$i]->save($item) && $saved;
        }

        return $saved;
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        $saved = true;
        $i = $this->adapterCount;

        while ($i--) {
            $saved = $this->adapters[$i]->saveDeferred($item) && $saved;
        }

        return $saved;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $committed = true;
        $i = $this->adapterCount;

        while ($i--) {
            $committed = $this->adapters[$i]->commit() && $committed;
        }

        return $committed;
    }

    /**
     * {@inheritdoc}
     */
    public function prune()
    {
        $pruned = true;

        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof PruneableInterface) {
                $pruned = $adapter->prune() && $pruned;
            }
        }

        return $pruned;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof ResetInterface) {
                $adapter->reset();
            }
        }
    }
}
