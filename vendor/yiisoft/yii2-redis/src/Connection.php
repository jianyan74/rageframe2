<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\redis;

use yii\base\Component;
use yii\db\Exception;
use yii\helpers\Inflector;

/**
 * The redis connection class is used to establish a connection to a [redis](http://redis.io/) server.
 *
 * By default it assumes there is a redis server running on localhost at port 6379 and uses the database number 0.
 *
 * It is possible to connect to a redis server using [[hostname]] and [[port]] or using a [[unixSocket]].
 *
 * It also supports [the AUTH command](http://redis.io/commands/auth) of redis.
 * When the server needs authentication, you can set the [[password]] property to
 * authenticate with the server after connect.
 *
 * The execution of [redis commands](http://redis.io/commands) is possible with via [[executeCommand()]].
 *
 * @method mixed append($key, $value) Append a value to a key. <https://redis.io/commands/append>
 * @method mixed auth($password) Authenticate to the server. <https://redis.io/commands/auth>
 * @method mixed bgrewriteaof() Asynchronously rewrite the append-only file. <https://redis.io/commands/bgrewriteaof>
 * @method mixed bgsave() Asynchronously save the dataset to disk. <https://redis.io/commands/bgsave>
 * @method mixed bitcount($key, $start = null, $end = null) Count set bits in a string. <https://redis.io/commands/bitcount>
 * @method mixed bitfield($key, ...$operations) Perform arbitrary bitfield integer operations on strings. <https://redis.io/commands/bitfield>
 * @method mixed bitop($operation, $destkey, ...$keys) Perform bitwise operations between strings. <https://redis.io/commands/bitop>
 * @method mixed bitpos($key, $bit, $start = null, $end = null) Find first bit set or clear in a string. <https://redis.io/commands/bitpos>
 * @method mixed blpop(...$keys, $timeout) Remove and get the first element in a list, or block until one is available. <https://redis.io/commands/blpop>
 * @method mixed brpop(...$keys, $timeout) Remove and get the last element in a list, or block until one is available. <https://redis.io/commands/brpop>
 * @method mixed brpoplpush($source, $destination, $timeout) Pop a value from a list, push it to another list and return it; or block until one is available. <https://redis.io/commands/brpoplpush>
 * @method mixed clientKill(...$filters) Kill the connection of a client. <https://redis.io/commands/client-kill>
 * @method mixed clientList() Get the list of client connections. <https://redis.io/commands/client-list>
 * @method mixed clientGetname() Get the current connection name. <https://redis.io/commands/client-getname>
 * @method mixed clientPause($timeout) Stop processing commands from clients for some time. <https://redis.io/commands/client-pause>
 * @method mixed clientReply($option) Instruct the server whether to reply to commands. <https://redis.io/commands/client-reply>
 * @method mixed clientSetname($connectionName) Set the current connection name. <https://redis.io/commands/client-setname>
 * @method mixed clusterAddslots(...$slots) Assign new hash slots to receiving node. <https://redis.io/commands/cluster-addslots>
 * @method mixed clusterCountkeysinslot($slot) Return the number of local keys in the specified hash slot. <https://redis.io/commands/cluster-countkeysinslot>
 * @method mixed clusterDelslots(...$slots) Set hash slots as unbound in receiving node. <https://redis.io/commands/cluster-delslots>
 * @method mixed clusterFailover($option = null) Forces a slave to perform a manual failover of its master.. <https://redis.io/commands/cluster-failover>
 * @method mixed clusterForget($nodeId) Remove a node from the nodes table. <https://redis.io/commands/cluster-forget>
 * @method mixed clusterGetkeysinslot($slot, $count) Return local key names in the specified hash slot. <https://redis.io/commands/cluster-getkeysinslot>
 * @method mixed clusterInfo() Provides info about Redis Cluster node state. <https://redis.io/commands/cluster-info>
 * @method mixed clusterKeyslot($key) Returns the hash slot of the specified key. <https://redis.io/commands/cluster-keyslot>
 * @method mixed clusterMeet($ip, $port) Force a node cluster to handshake with another node. <https://redis.io/commands/cluster-meet>
 * @method mixed clusterNodes() Get Cluster config for the node. <https://redis.io/commands/cluster-nodes>
 * @method mixed clusterReplicate($nodeId) Reconfigure a node as a slave of the specified master node. <https://redis.io/commands/cluster-replicate>
 * @method mixed clusterReset($resetType = "SOFT") Reset a Redis Cluster node. <https://redis.io/commands/cluster-reset>
 * @method mixed clusterSaveconfig() Forces the node to save cluster state on disk. <https://redis.io/commands/cluster-saveconfig>
 * @method mixed clusterSetslot($slot, $type, $nodeid = null) Bind a hash slot to a specific node. <https://redis.io/commands/cluster-setslot>
 * @method mixed clusterSlaves($nodeId) List slave nodes of the specified master node. <https://redis.io/commands/cluster-slaves>
 * @method mixed clusterSlots() Get array of Cluster slot to node mappings. <https://redis.io/commands/cluster-slots>
 * @method mixed command() Get array of Redis command details. <https://redis.io/commands/command>
 * @method mixed commandCount() Get total number of Redis commands. <https://redis.io/commands/command-count>
 * @method mixed commandGetkeys() Extract keys given a full Redis command. <https://redis.io/commands/command-getkeys>
 * @method mixed commandInfo(...$commandNames) Get array of specific Redis command details. <https://redis.io/commands/command-info>
 * @method mixed configGet($parameter) Get the value of a configuration parameter. <https://redis.io/commands/config-get>
 * @method mixed configRewrite() Rewrite the configuration file with the in memory configuration. <https://redis.io/commands/config-rewrite>
 * @method mixed configSet($parameter, $value) Set a configuration parameter to the given value. <https://redis.io/commands/config-set>
 * @method mixed configResetstat() Reset the stats returned by INFO. <https://redis.io/commands/config-resetstat>
 * @method mixed dbsize() Return the number of keys in the selected database. <https://redis.io/commands/dbsize>
 * @method mixed debugObject($key) Get debugging information about a key. <https://redis.io/commands/debug-object>
 * @method mixed debugSegfault() Make the server crash. <https://redis.io/commands/debug-segfault>
 * @method mixed decr($key) Decrement the integer value of a key by one. <https://redis.io/commands/decr>
 * @method mixed decrby($key, $decrement) Decrement the integer value of a key by the given number. <https://redis.io/commands/decrby>
 * @method mixed del(...$keys) Delete a key. <https://redis.io/commands/del>
 * @method mixed discard() Discard all commands issued after MULTI. <https://redis.io/commands/discard>
 * @method mixed dump($key) Return a serialized version of the value stored at the specified key.. <https://redis.io/commands/dump>
 * @method mixed echo($message) Echo the given string. <https://redis.io/commands/echo>
 * @method mixed eval($script, $numkeys, ...$keys, ...$args) Execute a Lua script server side. <https://redis.io/commands/eval>
 * @method mixed evalsha($sha1, $numkeys, ...$keys, ...$args) Execute a Lua script server side. <https://redis.io/commands/evalsha>
 * @method mixed exec() Execute all commands issued after MULTI. <https://redis.io/commands/exec>
 * @method mixed exists(...$keys) Determine if a key exists. <https://redis.io/commands/exists>
 * @method mixed expire($key, $seconds) Set a key's time to live in seconds. <https://redis.io/commands/expire>
 * @method mixed expireat($key, $timestamp) Set the expiration for a key as a UNIX timestamp. <https://redis.io/commands/expireat>
 * @method mixed flushall($ASYNC = null) Remove all keys from all databases. <https://redis.io/commands/flushall>
 * @method mixed flushdb($ASYNC = null) Remove all keys from the current database. <https://redis.io/commands/flushdb>
 * @method mixed geoadd($key, $longitude, $latitude, $member, ...$more) Add one or more geospatial items in the geospatial index represented using a sorted set. <https://redis.io/commands/geoadd>
 * @method mixed geohash($key, ...$members) Returns members of a geospatial index as standard geohash strings. <https://redis.io/commands/geohash>
 * @method mixed geopos($key, ...$members) Returns longitude and latitude of members of a geospatial index. <https://redis.io/commands/geopos>
 * @method mixed geodist($key, $member1, $member2, $unit = null) Returns the distance between two members of a geospatial index. <https://redis.io/commands/geodist>
 * @method mixed georadius($key, $longitude, $latitude, $radius, $metric, ...$options) Query a sorted set representing a geospatial index to fetch members matching a given maximum distance from a point. <https://redis.io/commands/georadius>
 * @method mixed georadiusbymember($key, $member, $radius, $metric, ...$options) Query a sorted set representing a geospatial index to fetch members matching a given maximum distance from a member. <https://redis.io/commands/georadiusbymember>
 * @method mixed get($key) Get the value of a key. <https://redis.io/commands/get>
 * @method mixed getbit($key, $offset) Returns the bit value at offset in the string value stored at key. <https://redis.io/commands/getbit>
 * @method mixed getrange($key, $start, $end) Get a substring of the string stored at a key. <https://redis.io/commands/getrange>
 * @method mixed getset($key, $value) Set the string value of a key and return its old value. <https://redis.io/commands/getset>
 * @method mixed hdel($key, ...$fields) Delete one or more hash fields. <https://redis.io/commands/hdel>
 * @method mixed hexists($key, $field) Determine if a hash field exists. <https://redis.io/commands/hexists>
 * @method mixed hget($key, $field) Get the value of a hash field. <https://redis.io/commands/hget>
 * @method mixed hgetall($key) Get all the fields and values in a hash. <https://redis.io/commands/hgetall>
 * @method mixed hincrby($key, $field, $increment) Increment the integer value of a hash field by the given number. <https://redis.io/commands/hincrby>
 * @method mixed hincrbyfloat($key, $field, $increment) Increment the float value of a hash field by the given amount. <https://redis.io/commands/hincrbyfloat>
 * @method mixed hkeys($key) Get all the fields in a hash. <https://redis.io/commands/hkeys>
 * @method mixed hlen($key) Get the number of fields in a hash. <https://redis.io/commands/hlen>
 * @method mixed hmget($key, ...$fields) Get the values of all the given hash fields. <https://redis.io/commands/hmget>
 * @method mixed hmset($key, $field, $value, ...$more) Set multiple hash fields to multiple values. <https://redis.io/commands/hmset>
 * @method mixed hset($key, $field, $value) Set the string value of a hash field. <https://redis.io/commands/hset>
 * @method mixed hsetnx($key, $field, $value) Set the value of a hash field, only if the field does not exist. <https://redis.io/commands/hsetnx>
 * @method mixed hstrlen($key, $field) Get the length of the value of a hash field. <https://redis.io/commands/hstrlen>
 * @method mixed hvals($key) Get all the values in a hash. <https://redis.io/commands/hvals>
 * @method mixed incr($key) Increment the integer value of a key by one. <https://redis.io/commands/incr>
 * @method mixed incrby($key, $increment) Increment the integer value of a key by the given amount. <https://redis.io/commands/incrby>
 * @method mixed incrbyfloat($key, $increment) Increment the float value of a key by the given amount. <https://redis.io/commands/incrbyfloat>
 * @method mixed info($section = null) Get information and statistics about the server. <https://redis.io/commands/info>
 * @method mixed keys($pattern) Find all keys matching the given pattern. <https://redis.io/commands/keys>
 * @method mixed lastsave() Get the UNIX time stamp of the last successful save to disk. <https://redis.io/commands/lastsave>
 * @method mixed lindex($key, $index) Get an element from a list by its index. <https://redis.io/commands/lindex>
 * @method mixed linsert($key, $where, $pivot, $value) Insert an element before or after another element in a list. <https://redis.io/commands/linsert>
 * @method mixed llen($key) Get the length of a list. <https://redis.io/commands/llen>
 * @method mixed lpop($key) Remove and get the first element in a list. <https://redis.io/commands/lpop>
 * @method mixed lpush($key, ...$values) Prepend one or multiple values to a list. <https://redis.io/commands/lpush>
 * @method mixed lpushx($key, $value) Prepend a value to a list, only if the list exists. <https://redis.io/commands/lpushx>
 * @method mixed lrange($key, $start, $stop) Get a range of elements from a list. <https://redis.io/commands/lrange>
 * @method mixed lrem($key, $count, $value) Remove elements from a list. <https://redis.io/commands/lrem>
 * @method mixed lset($key, $index, $value) Set the value of an element in a list by its index. <https://redis.io/commands/lset>
 * @method mixed ltrim($key, $start, $stop) Trim a list to the specified range. <https://redis.io/commands/ltrim>
 * @method mixed mget(...$keys) Get the values of all the given keys. <https://redis.io/commands/mget>
 * @method mixed migrate($host, $port, $key, $destinationDb, $timeout, ...$options) Atomically transfer a key from a Redis instance to another one.. <https://redis.io/commands/migrate>
 * @method mixed monitor() Listen for all requests received by the server in real time. <https://redis.io/commands/monitor>
 * @method mixed move($key, $db) Move a key to another database. <https://redis.io/commands/move>
 * @method mixed mset(...$keyValuePairs) Set multiple keys to multiple values. <https://redis.io/commands/mset>
 * @method mixed msetnx(...$keyValuePairs) Set multiple keys to multiple values, only if none of the keys exist. <https://redis.io/commands/msetnx>
 * @method mixed multi() Mark the start of a transaction block. <https://redis.io/commands/multi>
 * @method mixed object($subcommand, ...$argumentss) Inspect the internals of Redis objects. <https://redis.io/commands/object>
 * @method mixed persist($key) Remove the expiration from a key. <https://redis.io/commands/persist>
 * @method mixed pexpire($key, $milliseconds) Set a key's time to live in milliseconds. <https://redis.io/commands/pexpire>
 * @method mixed pexpireat($key, $millisecondsTimestamp) Set the expiration for a key as a UNIX timestamp specified in milliseconds. <https://redis.io/commands/pexpireat>
 * @method mixed pfadd($key, ...$elements) Adds the specified elements to the specified HyperLogLog.. <https://redis.io/commands/pfadd>
 * @method mixed pfcount(...$keys) Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s).. <https://redis.io/commands/pfcount>
 * @method mixed pfmerge($destkey, ...$sourcekeys) Merge N different HyperLogLogs into a single one.. <https://redis.io/commands/pfmerge>
 * @method mixed ping($message = null) Ping the server. <https://redis.io/commands/ping>
 * @method mixed psetex($key, $milliseconds, $value) Set the value and expiration in milliseconds of a key. <https://redis.io/commands/psetex>
 * @method mixed psubscribe(...$patterns) Listen for messages published to channels matching the given patterns. <https://redis.io/commands/psubscribe>
 * @method mixed pubsub($subcommand, ...$arguments) Inspect the state of the Pub/Sub subsystem. <https://redis.io/commands/pubsub>
 * @method mixed pttl($key) Get the time to live for a key in milliseconds. <https://redis.io/commands/pttl>
 * @method mixed publish($channel, $message) Post a message to a channel. <https://redis.io/commands/publish>
 * @method mixed punsubscribe(...$patterns) Stop listening for messages posted to channels matching the given patterns. <https://redis.io/commands/punsubscribe>
 * @method mixed quit() Close the connection. <https://redis.io/commands/quit>
 * @method mixed randomkey() Return a random key from the keyspace. <https://redis.io/commands/randomkey>
 * @method mixed readonly() Enables read queries for a connection to a cluster slave node. <https://redis.io/commands/readonly>
 * @method mixed readwrite() Disables read queries for a connection to a cluster slave node. <https://redis.io/commands/readwrite>
 * @method mixed rename($key, $newkey) Rename a key. <https://redis.io/commands/rename>
 * @method mixed renamenx($key, $newkey) Rename a key, only if the new key does not exist. <https://redis.io/commands/renamenx>
 * @method mixed restore($key, $ttl, $serializedValue, $REPLACE = null) Create a key using the provided serialized value, previously obtained using DUMP.. <https://redis.io/commands/restore>
 * @method mixed role() Return the role of the instance in the context of replication. <https://redis.io/commands/role>
 * @method mixed rpop($key) Remove and get the last element in a list. <https://redis.io/commands/rpop>
 * @method mixed rpoplpush($source, $destination) Remove the last element in a list, prepend it to another list and return it. <https://redis.io/commands/rpoplpush>
 * @method mixed rpush($key, ...$values) Append one or multiple values to a list. <https://redis.io/commands/rpush>
 * @method mixed rpushx($key, $value) Append a value to a list, only if the list exists. <https://redis.io/commands/rpushx>
 * @method mixed sadd($key, ...$members) Add one or more members to a set. <https://redis.io/commands/sadd>
 * @method mixed save() Synchronously save the dataset to disk. <https://redis.io/commands/save>
 * @method mixed scard($key) Get the number of members in a set. <https://redis.io/commands/scard>
 * @method mixed scriptDebug($option) Set the debug mode for executed scripts.. <https://redis.io/commands/script-debug>
 * @method mixed scriptExists(...$sha1s) Check existence of scripts in the script cache.. <https://redis.io/commands/script-exists>
 * @method mixed scriptFlush() Remove all the scripts from the script cache.. <https://redis.io/commands/script-flush>
 * @method mixed scriptKill() Kill the script currently in execution.. <https://redis.io/commands/script-kill>
 * @method mixed scriptLoad($script) Load the specified Lua script into the script cache.. <https://redis.io/commands/script-load>
 * @method mixed sdiff(...$keys) Subtract multiple sets. <https://redis.io/commands/sdiff>
 * @method mixed sdiffstore($destination, ...$keys) Subtract multiple sets and store the resulting set in a key. <https://redis.io/commands/sdiffstore>
 * @method mixed select($index) Change the selected database for the current connection. <https://redis.io/commands/select>
 * @method mixed set($key, $value, ...$options) Set the string value of a key. <https://redis.io/commands/set>
 * @method mixed setbit($key, $offset, $value) Sets or clears the bit at offset in the string value stored at key. <https://redis.io/commands/setbit>
 * @method mixed setex($key, $seconds, $value) Set the value and expiration of a key. <https://redis.io/commands/setex>
 * @method mixed setnx($key, $value) Set the value of a key, only if the key does not exist. <https://redis.io/commands/setnx>
 * @method mixed setrange($key, $offset, $value) Overwrite part of a string at key starting at the specified offset. <https://redis.io/commands/setrange>
 * @method mixed shutdown($saveOption = null) Synchronously save the dataset to disk and then shut down the server. <https://redis.io/commands/shutdown>
 * @method mixed sinter(...$keys) Intersect multiple sets. <https://redis.io/commands/sinter>
 * @method mixed sinterstore($destination, ...$keys) Intersect multiple sets and store the resulting set in a key. <https://redis.io/commands/sinterstore>
 * @method mixed sismember($key, $member) Determine if a given value is a member of a set. <https://redis.io/commands/sismember>
 * @method mixed slaveof($host, $port) Make the server a slave of another instance, or promote it as master. <https://redis.io/commands/slaveof>
 * @method mixed slowlog($subcommand, $argument = null) Manages the Redis slow queries log. <https://redis.io/commands/slowlog>
 * @method mixed smembers($key) Get all the members in a set. <https://redis.io/commands/smembers>
 * @method mixed smove($source, $destination, $member) Move a member from one set to another. <https://redis.io/commands/smove>
 * @method mixed sort($key, ...$options) Sort the elements in a list, set or sorted set. <https://redis.io/commands/sort>
 * @method mixed spop($key, $count = null) Remove and return one or multiple random members from a set. <https://redis.io/commands/spop>
 * @method mixed srandmember($key, $count = null) Get one or multiple random members from a set. <https://redis.io/commands/srandmember>
 * @method mixed srem($key, ...$members) Remove one or more members from a set. <https://redis.io/commands/srem>
 * @method mixed strlen($key) Get the length of the value stored in a key. <https://redis.io/commands/strlen>
 * @method mixed subscribe(...$channels) Listen for messages published to the given channels. <https://redis.io/commands/subscribe>
 * @method mixed sunion(...$keys) Add multiple sets. <https://redis.io/commands/sunion>
 * @method mixed sunionstore($destination, ...$keys) Add multiple sets and store the resulting set in a key. <https://redis.io/commands/sunionstore>
 * @method mixed swapdb($index, $index) Swaps two Redis databases. <https://redis.io/commands/swapdb>
 * @method mixed sync() Internal command used for replication. <https://redis.io/commands/sync>
 * @method mixed time() Return the current server time. <https://redis.io/commands/time>
 * @method mixed touch(...$keys) Alters the last access time of a key(s). Returns the number of existing keys specified.. <https://redis.io/commands/touch>
 * @method mixed ttl($key) Get the time to live for a key. <https://redis.io/commands/ttl>
 * @method mixed type($key) Determine the type stored at key. <https://redis.io/commands/type>
 * @method mixed unsubscribe(...$channels) Stop listening for messages posted to the given channels. <https://redis.io/commands/unsubscribe>
 * @method mixed unlink(...$keys) Delete a key asynchronously in another thread. Otherwise it is just as DEL, but non blocking.. <https://redis.io/commands/unlink>
 * @method mixed unwatch() Forget about all watched keys. <https://redis.io/commands/unwatch>
 * @method mixed wait($numslaves, $timeout) Wait for the synchronous replication of all the write commands sent in the context of the current connection. <https://redis.io/commands/wait>
 * @method mixed watch(...$keys) Watch the given keys to determine execution of the MULTI/EXEC block. <https://redis.io/commands/watch>
 * @method mixed zadd($key, ...$options) Add one or more members to a sorted set, or update its score if it already exists. <https://redis.io/commands/zadd>
 * @method mixed zcard($key) Get the number of members in a sorted set. <https://redis.io/commands/zcard>
 * @method mixed zcount($key, $min, $max) Count the members in a sorted set with scores within the given values. <https://redis.io/commands/zcount>
 * @method mixed zincrby($key, $increment, $member) Increment the score of a member in a sorted set. <https://redis.io/commands/zincrby>
 * @method mixed zinterstore($destination, $numkeys, $key, ...$options) Intersect multiple sorted sets and store the resulting sorted set in a new key. <https://redis.io/commands/zinterstore>
 * @method mixed zlexcount($key, $min, $max) Count the number of members in a sorted set between a given lexicographical range. <https://redis.io/commands/zlexcount>
 * @method mixed zrange($key, $start, $stop, $WITHSCORES = null) Return a range of members in a sorted set, by index. <https://redis.io/commands/zrange>
 * @method mixed zrangebylex($key, $min, $max, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by lexicographical range. <https://redis.io/commands/zrangebylex>
 * @method mixed zrevrangebylex($key, $max, $min, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by lexicographical range, ordered from higher to lower strings.. <https://redis.io/commands/zrevrangebylex>
 * @method mixed zrangebyscore($key, $min, $max, $WITHSCORES = null, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by score. <https://redis.io/commands/zrangebyscore>
 * @method mixed zrank($key, $member) Determine the index of a member in a sorted set. <https://redis.io/commands/zrank>
 * @method mixed zrem($key, ...$members) Remove one or more members from a sorted set. <https://redis.io/commands/zrem>
 * @method mixed zremrangebylex($key, $min, $max) Remove all members in a sorted set between the given lexicographical range. <https://redis.io/commands/zremrangebylex>
 * @method mixed zremrangebyrank($key, $start, $stop) Remove all members in a sorted set within the given indexes. <https://redis.io/commands/zremrangebyrank>
 * @method mixed zremrangebyscore($key, $min, $max) Remove all members in a sorted set within the given scores. <https://redis.io/commands/zremrangebyscore>
 * @method mixed zrevrange($key, $start, $stop, $WITHSCORES = null) Return a range of members in a sorted set, by index, with scores ordered from high to low. <https://redis.io/commands/zrevrange>
 * @method mixed zrevrangebyscore($key, $max, $min, $WITHSCORES = null, $LIMIT = null, $offset = null, $count = null) Return a range of members in a sorted set, by score, with scores ordered from high to low. <https://redis.io/commands/zrevrangebyscore>
 * @method mixed zrevrank($key, $member) Determine the index of a member in a sorted set, with scores ordered from high to low. <https://redis.io/commands/zrevrank>
 * @method mixed zscore($key, $member) Get the score associated with the given member in a sorted set. <https://redis.io/commands/zscore>
 * @method mixed zunionstore($destination, $numkeys, $key, ...$options) Add multiple sorted sets and store the resulting sorted set in a new key. <https://redis.io/commands/zunionstore>
 * @method mixed scan($cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate the keys space. <https://redis.io/commands/scan>
 * @method mixed sscan($key, $cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate Set elements. <https://redis.io/commands/sscan>
 * @method mixed hscan($key, $cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate hash fields and associated values. <https://redis.io/commands/hscan>
 * @method mixed zscan($key, $cursor, $MATCH = null, $pattern = null, $COUNT = null, $count = null) Incrementally iterate sorted sets elements and associated scores. <https://redis.io/commands/zscan>
 *
 * @property string $driverName Name of the DB driver. This property is read-only.
 * @property bool $isActive Whether the DB connection is established. This property is read-only.
 * @property LuaScriptBuilder $luaScriptBuilder This property is read-only.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class Connection extends Component
{
    /**
     * @event Event an event that is triggered after a DB connection is established
     */
    const EVENT_AFTER_OPEN = 'afterOpen';

    /**
     * @var string the hostname or ip address to use for connecting to the redis server. Defaults to 'localhost'.
     * If [[unixSocket]] is specified, hostname and [[port]] will be ignored.
     */
    public $hostname = 'localhost';
    /**
     * @var integer the port to use for connecting to the redis server. Default port is 6379.
     * If [[unixSocket]] is specified, [[hostname]] and port will be ignored.
     */
    public $port = 6379;
    /**
     * @var string the unix socket path (e.g. `/var/run/redis/redis.sock`) to use for connecting to the redis server.
     * This can be used instead of [[hostname]] and [[port]] to connect to the server using a unix socket.
     * If a unix socket path is specified, [[hostname]] and [[port]] will be ignored.
     * @since 2.0.1
     */
    public $unixSocket;
    /**
     * @var string the password for establishing DB connection. Defaults to null meaning no AUTH command is sent.
     * See http://redis.io/commands/auth
     */
    public $password;
    /**
     * @var integer the redis database to use. This is an integer value starting from 0. Defaults to 0.
     * Since version 2.0.6 you can disable the SELECT command sent after connection by setting this property to `null`.
     */
    public $database = 0;
    /**
     * @var float timeout to use for connection to redis. If not set the timeout set in php.ini will be used: `ini_get("default_socket_timeout")`.
     */
    public $connectionTimeout = null;
    /**
     * @var float timeout to use for redis socket when reading and writing data. If not set the php default value will be used.
     */
    public $dataTimeout = null;
    /**
     * @var integer Bitmask field which may be set to any combination of connection flags passed to [stream_socket_client()](http://php.net/manual/en/function.stream-socket-client.php).
     * Currently the select of connection flags is limited to `STREAM_CLIENT_CONNECT` (default), `STREAM_CLIENT_ASYNC_CONNECT` and `STREAM_CLIENT_PERSISTENT`.
     *
     * > Warning: `STREAM_CLIENT_PERSISTENT` will make PHP reuse connections to the same server. If you are using multiple
     * > connection objects to refer to different redis [[$database|databases]] on the same [[port]], redis commands may
     * > get executed on the wrong database. `STREAM_CLIENT_PERSISTENT` is only safe to use if you use only one database.
     * >
     * > You may still use persistent connections in this case when disambiguating ports as described
     * > in [a comment on the PHP manual](http://php.net/manual/en/function.stream-socket-client.php#105393)
     * > e.g. on the connection used for session storage, specify the port as:
     * >
     * > ```php
     * > 'port' => '6379/session'
     * > ```
     *
     * @see http://php.net/manual/en/function.stream-socket-client.php
     * @since 2.0.5
     */
    public $socketClientFlags = STREAM_CLIENT_CONNECT;
    /**
     * @var integer The number of times a command execution should be retried when a connection failure occurs.
     * This is used in [[executeCommand()]] when a [[SocketException]] is thrown.
     * Defaults to 0 meaning no retries on failure.
     * @since 2.0.7
     */
    public $retries = 0;
    /**
     * @var array List of available redis commands.
     * @see http://redis.io/commands
     */
    public $redisCommands = [
        'APPEND', // Append a value to a key
        'AUTH', // Authenticate to the server
        'BGREWRITEAOF', // Asynchronously rewrite the append-only file
        'BGSAVE', // Asynchronously save the dataset to disk
        'BITCOUNT', // Count set bits in a string
        'BITFIELD', // Perform arbitrary bitfield integer operations on strings
        'BITOP', // Perform bitwise operations between strings
        'BITPOS', // Find first bit set or clear in a string
        'BLPOP', // Remove and get the first element in a list, or block until one is available
        'BRPOP', // Remove and get the last element in a list, or block until one is available
        'BRPOPLPUSH', // Pop a value from a list, push it to another list and return it; or block until one is available
        'CLIENT KILL', // Kill the connection of a client
        'CLIENT LIST', // Get the list of client connections
        'CLIENT GETNAME', // Get the current connection name
        'CLIENT PAUSE', // Stop processing commands from clients for some time
        'CLIENT REPLY', // Instruct the server whether to reply to commands
        'CLIENT SETNAME', // Set the current connection name
        'CLUSTER ADDSLOTS', // Assign new hash slots to receiving node
        'CLUSTER COUNTKEYSINSLOT', // Return the number of local keys in the specified hash slot
        'CLUSTER DELSLOTS', // Set hash slots as unbound in receiving node
        'CLUSTER FAILOVER', // Forces a slave to perform a manual failover of its master.
        'CLUSTER FORGET', // Remove a node from the nodes table
        'CLUSTER GETKEYSINSLOT', // Return local key names in the specified hash slot
        'CLUSTER INFO', // Provides info about Redis Cluster node state
        'CLUSTER KEYSLOT', // Returns the hash slot of the specified key
        'CLUSTER MEET', // Force a node cluster to handshake with another node
        'CLUSTER NODES', // Get Cluster config for the node
        'CLUSTER REPLICATE', // Reconfigure a node as a slave of the specified master node
        'CLUSTER RESET', // Reset a Redis Cluster node
        'CLUSTER SAVECONFIG', // Forces the node to save cluster state on disk
        'CLUSTER SETSLOT', // Bind a hash slot to a specific node
        'CLUSTER SLAVES', // List slave nodes of the specified master node
        'CLUSTER SLOTS', // Get array of Cluster slot to node mappings
        'COMMAND', // Get array of Redis command details
        'COMMAND COUNT', // Get total number of Redis commands
        'COMMAND GETKEYS', // Extract keys given a full Redis command
        'COMMAND INFO', // Get array of specific Redis command details
        'CONFIG GET', // Get the value of a configuration parameter
        'CONFIG REWRITE', // Rewrite the configuration file with the in memory configuration
        'CONFIG SET', // Set a configuration parameter to the given value
        'CONFIG RESETSTAT', // Reset the stats returned by INFO
        'DBSIZE', // Return the number of keys in the selected database
        'DEBUG OBJECT', // Get debugging information about a key
        'DEBUG SEGFAULT', // Make the server crash
        'DECR', // Decrement the integer value of a key by one
        'DECRBY', // Decrement the integer value of a key by the given number
        'DEL', // Delete a key
        'DISCARD', // Discard all commands issued after MULTI
        'DUMP', // Return a serialized version of the value stored at the specified key.
        'ECHO', // Echo the given string
        'EVAL', // Execute a Lua script server side
        'EVALSHA', // Execute a Lua script server side
        'EXEC', // Execute all commands issued after MULTI
        'EXISTS', // Determine if a key exists
        'EXPIRE', // Set a key's time to live in seconds
        'EXPIREAT', // Set the expiration for a key as a UNIX timestamp
        'FLUSHALL', // Remove all keys from all databases
        'FLUSHDB', // Remove all keys from the current database
        'GEOADD', // Add one or more geospatial items in the geospatial index represented using a sorted set
        'GEOHASH', // Returns members of a geospatial index as standard geohash strings
        'GEOPOS', // Returns longitude and latitude of members of a geospatial index
        'GEODIST', // Returns the distance between two members of a geospatial index
        'GEORADIUS', // Query a sorted set representing a geospatial index to fetch members matching a given maximum distance from a point
        'GEORADIUSBYMEMBER', // Query a sorted set representing a geospatial index to fetch members matching a given maximum distance from a member
        'GET', // Get the value of a key
        'GETBIT', // Returns the bit value at offset in the string value stored at key
        'GETRANGE', // Get a substring of the string stored at a key
        'GETSET', // Set the string value of a key and return its old value
        'HDEL', // Delete one or more hash fields
        'HEXISTS', // Determine if a hash field exists
        'HGET', // Get the value of a hash field
        'HGETALL', // Get all the fields and values in a hash
        'HINCRBY', // Increment the integer value of a hash field by the given number
        'HINCRBYFLOAT', // Increment the float value of a hash field by the given amount
        'HKEYS', // Get all the fields in a hash
        'HLEN', // Get the number of fields in a hash
        'HMGET', // Get the values of all the given hash fields
        'HMSET', // Set multiple hash fields to multiple values
        'HSET', // Set the string value of a hash field
        'HSETNX', // Set the value of a hash field, only if the field does not exist
        'HSTRLEN', // Get the length of the value of a hash field
        'HVALS', // Get all the values in a hash
        'INCR', // Increment the integer value of a key by one
        'INCRBY', // Increment the integer value of a key by the given amount
        'INCRBYFLOAT', // Increment the float value of a key by the given amount
        'INFO', // Get information and statistics about the server
        'KEYS', // Find all keys matching the given pattern
        'LASTSAVE', // Get the UNIX time stamp of the last successful save to disk
        'LINDEX', // Get an element from a list by its index
        'LINSERT', // Insert an element before or after another element in a list
        'LLEN', // Get the length of a list
        'LPOP', // Remove and get the first element in a list
        'LPUSH', // Prepend one or multiple values to a list
        'LPUSHX', // Prepend a value to a list, only if the list exists
        'LRANGE', // Get a range of elements from a list
        'LREM', // Remove elements from a list
        'LSET', // Set the value of an element in a list by its index
        'LTRIM', // Trim a list to the specified range
        'MGET', // Get the values of all the given keys
        'MIGRATE', // Atomically transfer a key from a Redis instance to another one.
        'MONITOR', // Listen for all requests received by the server in real time
        'MOVE', // Move a key to another database
        'MSET', // Set multiple keys to multiple values
        'MSETNX', // Set multiple keys to multiple values, only if none of the keys exist
        'MULTI', // Mark the start of a transaction block
        'OBJECT', // Inspect the internals of Redis objects
        'PERSIST', // Remove the expiration from a key
        'PEXPIRE', // Set a key's time to live in milliseconds
        'PEXPIREAT', // Set the expiration for a key as a UNIX timestamp specified in milliseconds
        'PFADD', // Adds the specified elements to the specified HyperLogLog.
        'PFCOUNT', // Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s).
        'PFMERGE', // Merge N different HyperLogLogs into a single one.
        'PING', // Ping the server
        'PSETEX', // Set the value and expiration in milliseconds of a key
        'PSUBSCRIBE', // Listen for messages published to channels matching the given patterns
        'PUBSUB', // Inspect the state of the Pub/Sub subsystem
        'PTTL', // Get the time to live for a key in milliseconds
        'PUBLISH', // Post a message to a channel
        'PUNSUBSCRIBE', // Stop listening for messages posted to channels matching the given patterns
        'QUIT', // Close the connection
        'RANDOMKEY', // Return a random key from the keyspace
        'READONLY', // Enables read queries for a connection to a cluster slave node
        'READWRITE', // Disables read queries for a connection to a cluster slave node
        'RENAME', // Rename a key
        'RENAMENX', // Rename a key, only if the new key does not exist
        'RESTORE', // Create a key using the provided serialized value, previously obtained using DUMP.
        'ROLE', // Return the role of the instance in the context of replication
        'RPOP', // Remove and get the last element in a list
        'RPOPLPUSH', // Remove the last element in a list, prepend it to another list and return it
        'RPUSH', // Append one or multiple values to a list
        'RPUSHX', // Append a value to a list, only if the list exists
        'SADD', // Add one or more members to a set
        'SAVE', // Synchronously save the dataset to disk
        'SCARD', // Get the number of members in a set
        'SCRIPT DEBUG', // Set the debug mode for executed scripts.
        'SCRIPT EXISTS', // Check existence of scripts in the script cache.
        'SCRIPT FLUSH', // Remove all the scripts from the script cache.
        'SCRIPT KILL', // Kill the script currently in execution.
        'SCRIPT LOAD', // Load the specified Lua script into the script cache.
        'SDIFF', // Subtract multiple sets
        'SDIFFSTORE', // Subtract multiple sets and store the resulting set in a key
        'SELECT', // Change the selected database for the current connection
        'SET', // Set the string value of a key
        'SETBIT', // Sets or clears the bit at offset in the string value stored at key
        'SETEX', // Set the value and expiration of a key
        'SETNX', // Set the value of a key, only if the key does not exist
        'SETRANGE', // Overwrite part of a string at key starting at the specified offset
        'SHUTDOWN', // Synchronously save the dataset to disk and then shut down the server
        'SINTER', // Intersect multiple sets
        'SINTERSTORE', // Intersect multiple sets and store the resulting set in a key
        'SISMEMBER', // Determine if a given value is a member of a set
        'SLAVEOF', // Make the server a slave of another instance, or promote it as master
        'SLOWLOG', // Manages the Redis slow queries log
        'SMEMBERS', // Get all the members in a set
        'SMOVE', // Move a member from one set to another
        'SORT', // Sort the elements in a list, set or sorted set
        'SPOP', // Remove and return one or multiple random members from a set
        'SRANDMEMBER', // Get one or multiple random members from a set
        'SREM', // Remove one or more members from a set
        'STRLEN', // Get the length of the value stored in a key
        'SUBSCRIBE', // Listen for messages published to the given channels
        'SUNION', // Add multiple sets
        'SUNIONSTORE', // Add multiple sets and store the resulting set in a key
        'SWAPDB', // Swaps two Redis databases
        'SYNC', // Internal command used for replication
        'TIME', // Return the current server time
        'TOUCH', // Alters the last access time of a key(s). Returns the number of existing keys specified.
        'TTL', // Get the time to live for a key
        'TYPE', // Determine the type stored at key
        'UNSUBSCRIBE', // Stop listening for messages posted to the given channels
        'UNLINK', // Delete a key asynchronously in another thread. Otherwise it is just as DEL, but non blocking.
        'UNWATCH', // Forget about all watched keys
        'WAIT', // Wait for the synchronous replication of all the write commands sent in the context of the current connection
        'WATCH', // Watch the given keys to determine execution of the MULTI/EXEC block
        'ZADD', // Add one or more members to a sorted set, or update its score if it already exists
        'ZCARD', // Get the number of members in a sorted set
        'ZCOUNT', // Count the members in a sorted set with scores within the given values
        'ZINCRBY', // Increment the score of a member in a sorted set
        'ZINTERSTORE', // Intersect multiple sorted sets and store the resulting sorted set in a new key
        'ZLEXCOUNT', // Count the number of members in a sorted set between a given lexicographical range
        'ZRANGE', // Return a range of members in a sorted set, by index
        'ZRANGEBYLEX', // Return a range of members in a sorted set, by lexicographical range
        'ZREVRANGEBYLEX', // Return a range of members in a sorted set, by lexicographical range, ordered from higher to lower strings.
        'ZRANGEBYSCORE', // Return a range of members in a sorted set, by score
        'ZRANK', // Determine the index of a member in a sorted set
        'ZREM', // Remove one or more members from a sorted set
        'ZREMRANGEBYLEX', // Remove all members in a sorted set between the given lexicographical range
        'ZREMRANGEBYRANK', // Remove all members in a sorted set within the given indexes
        'ZREMRANGEBYSCORE', // Remove all members in a sorted set within the given scores
        'ZREVRANGE', // Return a range of members in a sorted set, by index, with scores ordered from high to low
        'ZREVRANGEBYSCORE', // Return a range of members in a sorted set, by score, with scores ordered from high to low
        'ZREVRANK', // Determine the index of a member in a sorted set, with scores ordered from high to low
        'ZSCORE', // Get the score associated with the given member in a sorted set
        'ZUNIONSTORE', // Add multiple sorted sets and store the resulting sorted set in a new key
        'SCAN', // Incrementally iterate the keys space
        'SSCAN', // Incrementally iterate Set elements
        'HSCAN', // Incrementally iterate hash fields and associated values
        'ZSCAN', // Incrementally iterate sorted sets elements and associated scores
    ];

    /**
     * @var resource redis socket connection
     */
    private $_socket = false;


    /**
     * Closes the connection when this component is being serialized.
     * @return array
     */
    public function __sleep()
    {
        $this->close();
        return array_keys(get_object_vars($this));
    }

    /**
     * Returns a value indicating whether the DB connection is established.
     * @return bool whether the DB connection is established
     */
    public function getIsActive()
    {
        return $this->_socket !== false;
    }

    /**
     * Establishes a DB connection.
     * It does nothing if a DB connection has already been established.
     * @throws Exception if connection fails
     */
    public function open()
    {
        if ($this->_socket !== false) {
            return;
        }
        $connection = ($this->unixSocket ?: $this->hostname . ':' . $this->port) . ', database=' . $this->database;
        \Yii::trace('Opening redis DB connection: ' . $connection, __METHOD__);
        $this->_socket = @stream_socket_client(
            $this->unixSocket ? 'unix://' . $this->unixSocket : 'tcp://' . $this->hostname . ':' . $this->port,
            $errorNumber,
            $errorDescription,
            $this->connectionTimeout ? $this->connectionTimeout : ini_get('default_socket_timeout'),
            $this->socketClientFlags
        );
        if ($this->_socket) {
            if ($this->dataTimeout !== null) {
                stream_set_timeout($this->_socket, $timeout = (int) $this->dataTimeout, (int) (($this->dataTimeout - $timeout) * 1000000));
            }
            if ($this->password !== null) {
                $this->executeCommand('AUTH', [$this->password]);
            }
            if ($this->database !== null) {
                $this->executeCommand('SELECT', [$this->database]);
            }
            $this->initConnection();
        } else {
            \Yii::error("Failed to open redis DB connection ($connection): $errorNumber - $errorDescription", __CLASS__);
            $message = YII_DEBUG ? "Failed to open redis DB connection ($connection): $errorNumber - $errorDescription" : 'Failed to open DB connection.';
            throw new Exception($message, $errorDescription, $errorNumber);
        }
    }

    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     */
    public function close()
    {
        if ($this->_socket !== false) {
            $connection = ($this->unixSocket ?: $this->hostname . ':' . $this->port) . ', database=' . $this->database;
            \Yii::trace('Closing DB connection: ' . $connection, __METHOD__);
            try {
                $this->executeCommand('QUIT');
            } catch (SocketException $e) {
                // ignore errors when quitting a closed connection
            }
            fclose($this->_socket);
            $this->_socket = false;
        }
    }

    /**
     * Initializes the DB connection.
     * This method is invoked right after the DB connection is established.
     * The default implementation triggers an [[EVENT_AFTER_OPEN]] event.
     */
    protected function initConnection()
    {
        $this->trigger(self::EVENT_AFTER_OPEN);
    }

    /**
     * Returns the name of the DB driver for the current [[dsn]].
     * @return string name of the DB driver
     */
    public function getDriverName()
    {
        return 'redis';
    }

    /**
     * @return LuaScriptBuilder
     */
    public function getLuaScriptBuilder()
    {
        return new LuaScriptBuilder();
    }

    /**
     * Allows issuing all supported commands via magic methods.
     *
     * ```php
     * $redis->hmset('test_collection', 'key1', 'val1', 'key2', 'val2')
     * ```
     *
     * @param string $name name of the missing method to execute
     * @param array $params method call arguments
     * @return mixed
     */
    public function __call($name, $params)
    {
        $redisCommand = strtoupper(Inflector::camel2words($name, false));
        if (in_array($redisCommand, $this->redisCommands)) {
            return $this->executeCommand($redisCommand, $params);
        } else {
            return parent::__call($name, $params);
        }
    }

    /**
     * Executes a redis command.
     * For a list of available commands and their parameters see http://redis.io/commands.
     *
     * The params array should contain the params separated by white space, e.g. to execute
     * `SET mykey somevalue NX` call the following:
     *
     * ```php
     * $redis->executeCommand('SET', ['mykey', 'somevalue', 'NX']);
     * ```
     *
     * @param string $name the name of the command
     * @param array $params list of parameters for the command
     * @return array|bool|null|string Dependent on the executed command this method
     * will return different data types:
     *
     * - `true` for commands that return "status reply" with the message `'OK'` or `'PONG'`.
     * - `string` for commands that return "status reply" that does not have the message `OK` (since version 2.0.1).
     * - `string` for commands that return "integer reply"
     *   as the value is in the range of a signed 64 bit integer.
     * - `string` or `null` for commands that return "bulk reply".
     * - `array` for commands that return "Multi-bulk replies".
     *
     * See [redis protocol description](http://redis.io/topics/protocol)
     * for details on the mentioned reply types.
     * @throws Exception for commands that return [error reply](http://redis.io/topics/protocol#error-reply).
     */
    public function executeCommand($name, $params = [])
    {
        $this->open();

        $params = array_merge(explode(' ', $name), $params);
        $command = '';
        $paramsCount = 0;
        foreach ($params as $arg) {
            if ($arg === null) {
                continue;
            }

            $paramsCount++;
            $command .= '$' . mb_strlen($arg, '8bit') . "\r\n" . $arg . "\r\n";
        }
        $command = '*' . $paramsCount . "\r\n" . $command;

        \Yii::trace("Executing Redis Command: {$name}", __METHOD__);
        if ($this->retries > 0) {
            $tries = $this->retries;
            while ($tries-- > 0) {
                try {
                    return $this->sendCommandInternal($command, $params);
                } catch (SocketException $e) {
                    \Yii::error($e, __METHOD__);
                    // backup retries, fail on commands that fail inside here
                    $retries = $this->retries;
                    $this->retries = 0;
                    $this->close();
                    $this->open();
                    $this->retries = $retries;
                }
            }
        }
        return $this->sendCommandInternal($command, $params);
    }

    /**
     * Sends RAW command string to the server.
     * @throws SocketException on connection error.
     */
    private function sendCommandInternal($command, $params)
    {
        $written = @fwrite($this->_socket, $command);
        if ($written === false) {
            throw new SocketException("Failed to write to socket.\nRedis command was: " . $command);
        }
        if ($written !== ($len = mb_strlen($command, '8bit'))) {
            throw new SocketException("Failed to write to socket. $written of $len bytes written.\nRedis command was: " . $command);
        }
        return $this->parseResponse(implode(' ', $params));
    }

    /**
     * @param string $command
     * @return mixed
     * @throws Exception on error
     */
    private function parseResponse($command)
    {
        if (($line = fgets($this->_socket)) === false) {
            throw new SocketException("Failed to read from socket.\nRedis command was: " . $command);
        }
        $type = $line[0];
        $line = mb_substr($line, 1, -2, '8bit');
        switch ($type) {
            case '+': // Status reply
                if ($line === 'OK' || $line === 'PONG') {
                    return true;
                } else {
                    return $line;
                }
            case '-': // Error reply
                throw new Exception("Redis error: " . $line . "\nRedis command was: " . $command);
            case ':': // Integer reply
                // no cast to int as it is in the range of a signed 64 bit integer
                return $line;
            case '$': // Bulk replies
                if ($line == '-1') {
                    return null;
                }
                $length = (int)$line + 2;
                $data = '';
                while ($length > 0) {
                    if (($block = fread($this->_socket, $length)) === false) {
                        throw new SocketException("Failed to read from socket.\nRedis command was: " . $command);
                    }
                    $data .= $block;
                    $length -= mb_strlen($block, '8bit');
                }

                return mb_substr($data, 0, -2, '8bit');
            case '*': // Multi-bulk replies
                $count = (int) $line;
                $data = [];
                for ($i = 0; $i < $count; $i++) {
                    $data[] = $this->parseResponse($command);
                }

                return $data;
            default:
                throw new Exception('Received illegal data from redis: ' . $line . "\nRedis command was: " . $command);
        }
    }
}
