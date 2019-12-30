<?php

namespace App\Http\Controllers;

use App\Models\Value;
use App\Http\Requests\StoreValuesRequest;

use App\Events\ValuesWriteOperation;
use App\Events\ValuesReadOperation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ValueController extends Controller
{
    /**
     * check for values in redis
     * if nothing found, get from db
     */
    public function index(Request $request)
    {
        if($request->has('keys')) {
            $keys = explode(',', $request->input('keys'));
        } else {
            $keys = null;
        }

        $data = $this->loadFromCache($keys);
        if ($data->isEmpty())
            $data = $this->loadFromDB($keys);

        event(new ValuesReadOperation(
            $data->toArray(),
            $this->getExpiresAt()
        ));

        $values = $data->pluck('value', 'key');
        return $values;
    }

    /**
     * get processed values from Custom Requet
     * bulk insert, ignore duplicates
     */
    public function store(StoreValuesRequest $request) // payload already processed
    {
        $data = $request->all();

        Value::insertOrIgnore($data);

        event(new ValuesWriteOperation($data));

        return response()->json();
    }

    /**
     * get values from request
     * get existing values from db
     * update values within transaction
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $expiresAt = $this->getExpiresAt();
        $values = Value::whereIn('key', array_keys($data))->get();

        DB::transaction(function () use ($values, $data, $expiresAt) {
            foreach($values as $value) {
                $value->value = $data[$value->key];
                $value->expires_at = $expiresAt;
                $value->save();
            }
        }, 2);

        event(new ValuesWriteOperation($values->toArray()));

        return response()->json();
    }

    private function loadFromDB($keys)
    {
        /**
         * I simply could use Eloquent but when working with large table,
         * Eloquent (with chunk too) is slow for some obvious reasons!
         * In this scenario, I don't need the added benefits of Eloquent.
         * So I used DB Facade for a little performance boost.
         */
        if(is_null($keys)) {
            $data = DB::table('values')
                        ->select('id', 'key', 'value')
                        ->get();
        } else {
            $data = DB::table('values')
                        ->select('id', 'key', 'value')
                        ->whereIn('key', $keys)
                        ->get();
        }

        return $data;
    }

    private function loadFromCache($keys)
    {
        if(is_null($keys)) {
            $keys = $this->getAllCacheKeys();
            if (empty($keys)) {
                return collect(); // cache has no records
            }
        }

        $cacheKeys = array_map(function($key) {
            return 'key:'.$key; // append prefix
        }, $keys);

        $data = Redis::mget($cacheKeys);
        if (empty($data)) {
            return collect(); // cache may have records but nothing with these keys
        }

        // process cache data
        $values = collect();
        foreach(array_combine($keys, $data) as $key => $value) {
            $values->push([
                'key' => $key,
                'value' => $value
            ]);
        }

        return $values;
    }

    private function getAllCacheKeys()
    {
        $keys = Redis::command('keys', ['key:*']);

        // remove prefix
        $prefix = config('database.redis.options.prefix').'key:';
        $keys = $cacheKeys = array_map(function($key) use ($prefix) {
            return substr($key, strlen($prefix));
        }, $keys);

        return $keys;
    }

    private function getExpiresAt()
    {
        $ttl = config('app.ttl');
        return now()->addMinutes($ttl);
    }
}
