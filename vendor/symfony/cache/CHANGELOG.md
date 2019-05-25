CHANGELOG
=========

4.2.0
-----

 * added support for connecting to Redis clusters via DSN
 * added support for configuring multiple Memcached servers via DSN
 * added `MarshallerInterface` and `DefaultMarshaller` to allow changing the serializer and provide one that automatically uses igbinary when available
 * implemented `CacheInterface`, which provides stampede protection via probabilistic early expiration and should become the preferred way to use a cache
 * added sub-second expiry accuracy for backends that support it
 * added support for phpredis 4 `compression` and `tcp_keepalive` options
 * added automatic table creation when using Doctrine DBAL with PDO-based backends
 * throw `LogicException` when `CacheItem::tag()` is called on an item coming from a non tag-aware pool
 * deprecated `CacheItem::getPreviousTags()`, use `CacheItem::getMetadata()` instead
 * deprecated the `AbstractAdapter::unserialize()` and `AbstractCache::unserialize()` methods
 * added `CacheCollectorPass` (originally in `FrameworkBundle`)
 * added `CachePoolClearerPass` (originally in `FrameworkBundle`)
 * added `CachePoolPass` (originally in `FrameworkBundle`)
 * added `CachePoolPrunerPass` (originally in `FrameworkBundle`)

3.4.0
-----

 * added using options from Memcached DSN
 * added PruneableInterface so PSR-6 or PSR-16 cache implementations can declare support for manual stale cache pruning
 * added prune logic to FilesystemTrait, PhpFilesTrait, PdoTrait, TagAwareAdapter and ChainTrait
 * now FilesystemAdapter, PhpFilesAdapter, FilesystemCache, PhpFilesCache, PdoAdapter, PdoCache, ChainAdapter, and
   ChainCache implement PruneableInterface and support manual stale cache pruning

3.3.0
-----

 * added CacheItem::getPreviousTags() to get bound tags coming from the pool storage if any
 * added PSR-16 "Simple Cache" implementations for all existing PSR-6 adapters
 * added Psr6Cache and SimpleCacheAdapter for bidirectional interoperability between PSR-6 and PSR-16
 * added MemcachedAdapter (PSR-6) and MemcachedCache (PSR-16)
 * added TraceableAdapter (PSR-6) and TraceableCache (PSR-16)

3.2.0
-----

 * added TagAwareAdapter for tags-based invalidation
 * added PdoAdapter with PDO and Doctrine DBAL support
 * added PhpArrayAdapter and PhpFilesAdapter for OPcache-backed shared memory storage (PHP 7+ only)
 * added NullAdapter

3.1.0
-----

 * added the component with strict PSR-6 implementations
 * added ApcuAdapter, ArrayAdapter, FilesystemAdapter and RedisAdapter
 * added AbstractAdapter, ChainAdapter and ProxyAdapter
 * added DoctrineAdapter and DoctrineProvider for bidirectional interoperability with Doctrine Cache
