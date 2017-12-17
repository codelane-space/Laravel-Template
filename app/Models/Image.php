<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

class Image extends AppModel
{
    protected static $table_name = 'images';
    protected static $writable_columns = [
        'user_id', 'source_id',
        'title', 'description',
        'filename', 'type'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * Get single data
     *
     * @param $id
     * @param string $column
     * @return null
     */
    public static function single($id, $column = 'id')
    {
        if (!$id) {
            return null;
        }
        return self::fetch([
            'single' => true,
            $column => $id
        ]);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function fetch($params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';
        $query = self::select($select);

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name);

        $query->orderBy('created_at', 'DESC');

        if (isset($params['object'])) {
            return $query;
        } else {
            if (isset($params['single'])) {
                return self::_format($query->first(), $params);
            } else if (isset($params['all'])) {
                return self::_format($query->get(), $params);
            } else {
                $query = paginate($query);

                return self::_format($query, $params);
            }
        }
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function getAll($params = [])
    {
        $params['all'] = true;
        return self::fetch($params);
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @param array $params
     * @return null
     */
    public static function _format($query, $params = [])
    {
        if (isset($params['single'])) {
            if (!$query) {
                return null;
            }

            // image path
            $query->path = get_image($query->filename, $query->type);

            // uploader full name
            $user = User::find($query->user_id);

            $query->full_name = ($user) ? $user->first_name . ' ' . $user->last_name : 'n/a';
        } else {
            foreach ($query as $row) {
                // image path
                $row->path = get_image($row->filename, $row->type);

                // uploader full name
                $user = User::find($row->user_id);

                $row->full_name = ($user) ? $user->first_name . ' ' . $user->last_name : 'n/a';
            }
        }

        return $query;
    }

    /**
     * Store new data
     *
     * @param array $inputs
     * @return bool
     */
    public static function store($inputs = [])
    {
        $store = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $store[$key] = $value;
            }
        }
        $store['created_at'] = sql_date();

        return (int)self::insertGetId($store);
    }

    /**
     * Delete source
     *
     * @param $source_id
     * @param $type
     */
    public static function destroySource($source_id, $type)
    {
        $image = self::where('source_id', $source_id)->where('type', $type)->get();

        foreach ($image as $row) {
            self::remove($row->id);
        }
    }

    /**
     * Delete data
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function remove($id)
    {
        $image = self::find($id);
        if ($image) {
            self::_deleteFile($image->filename);
        }

        return (bool)self::destroy($id);
    }

    /**
     * Multiple delete
     *
     * @param $source_id
     * @param $type
     * @return bool
     */
    public static function batchRemove($source_id, $type)
    {
        $success = true;
        $images = self::getAll([
            'source_id' => $source_id,
            'type' => $type
        ]);

        foreach ($images as $row) {
            if (!self::remove($row->id)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Delete image
     *
     * @param $file
     */
    private static function _deleteFile($file)
    {
        delete_file('private/img/' . $file);
    }
}
