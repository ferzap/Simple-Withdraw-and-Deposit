<?php

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\isNull;

if (!function_exists('create_pin')) {
    function create_pin($length = 6, $number = false)
    {
        if (!$number) {
            $passwdstr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        } else {
            $passwdstr = "1234567809";
        }
        $passwd = '';
        for ($i = 0; $i < strlen($passwdstr); $i++) {
            $passwdchars[$i] = $passwdstr[$i];
        }

        // randomize the chars
        srand((float)microtime() * 1000000);
        shuffle($passwdchars);

        for ($i = 0; $i < $length; $i++) {
            $charnum = rand(1, count($passwdchars));
            $passwd .= $passwdchars[$charnum - 1];
        }

        $passwd = substr($passwd, 0, $length);

        if (substr($passwd, 0, 1) == '0') {
            $passwd = '1' . substr($passwd, 1);
        }

        return $passwd;
    }
}

if (!function_exists('queryFilter')) {
    function queryFilter($query, $filters)
    {
        foreach ($filters as $key => $filter) {
            $arr = [];
            $explode = explode('_', $filter['search_type']);
            if (isset($explode[0])) {
                if ($explode[0] == 'string') {
                    str_contains($explode[0], '!') ? $arr['comparison'] = 'not like' : $arr['comparison'] = 'like';
                    if (count($explode) > 1) {
                        if ($explode[1] == 'start') $arr['value'] = $filter['value'] . "%";
                        if ($explode[1] == 'end') $arr['value'] = "%" . $filter['value'];
                    } else {
                        $arr['value'] = "%" . $filter['value'] . "%";
                    }

                    $query->Where($filter['field'], $arr['comparison'], $arr['value']);
                }

                if ($explode[0] == 'integer') {
                    str_contains($explode[0], '!') ? $arr['comparison'] = '!=' : $arr['comparison'] = '=';
                    if (count($explode) > 1) {
                        if ($explode[1] == 'min') {
                            str_contains($explode[0], '!') ? $arr['comparison'] = '!>=' : $arr['comparison'] = '>=';
                        }
                        if ($explode[1] == 'max') {
                            str_contains($explode[0], '!') ? $arr['comparison'] = '!<=' : $arr['comparison'] = '<=';
                        }
                    }

                    $query->Where($filter['field'], $arr['comparison'], $filter['value']);
                }

                if ($explode[0] == 'between' || $explode[0] == 'serial') {
                    if (str_contains($explode[0], '!')) {
                        $query->whereNotBetween($filter['field'], $filter['value']);
                    } else {
                        if ($explode[0] == 'serial') {
                            $serialGroup = explode('.', $filter['value']);
                            if (count($serialGroup) > 1) {
                                foreach ($serialGroup as $key => $value) {
                                    $serial = explode('-', $value);
                                    if (count($serial) > 1) {
                                        if ($key == 0) {
                                            $query->whereBetween($filter['field'], [$serial[0], $serial[1]]);
                                        } else {
                                            $query->orWhereBetween($filter['field'], [$serial[0], $serial[1]]);
                                        }
                                    } else {
                                        if ($key == 0) {
                                            $query->where($filter['field'], $serial[0]);
                                        } else {
                                            $query->orWhere($filter['field'], $serial[0]);
                                        }
                                    }
                                }
                            } else {
                                $serial = explode('-', $filter['value']);
                                if (count($serial) > 1) {
                                    $query->whereBetween($filter['field'], [$serial[0], $serial[1]]);
                                } else {
                                    $query->where($filter['field'], $serial[0]);
                                }
                            }
                        } else {
                            $query->whereBetween($filter['field'], $filter['value']);
                        }
                    }
                }

                if ($explode[0] == 'in') {
                    if (str_contains($explode[0], '!')) {
                        $query->whereNotIn($filter['field'], $filter['value']);
                    } else {
                        $query->WhereIn($filter['field'], $filter['value']);
                    }
                }

                if ($explode[0] == 'date') {
                    if (str_contains($filter['name'], 'Start')) {
                        $query->whereRaw("DATE(" . $filter['field'] . ") >= " . "'" . $filter['value'] . "'");
                    }
                    if (str_contains($filter['name'], 'End')) {
                        $query->whereRaw("DATE(" . $filter['field'] . ") <= " . "'" . $filter['value'] . "'");
                    }
                }
            }
        }

        return $query;
    }
}

if (!function_exists('querySerials')) {
    function querySerials($query, $input, $type = 'product')
    {
        if ($type == 'product') {
            $select = 'serial';
        }
        if ($type == 'membership') {
            $select = 'serial_code';
        }
        if ($type == 'productStock') {
            $select = 'serial_ro_stock_serial_id';
        }
        if ($type == 'membershipStock') {
            $select = 'serial_ro_stock_serial_id';
        }

        // check "." (dot) to separate each group serial
        if (str_contains($input, ".")) {
            $serialGroup = explode('.', $input);
            $query->Where(function (Builder $query) use ($select, $serialGroup) {
                foreach ($serialGroup as $key => $value) {
                    // check "-" to separate serial range
                    $serial = explode('-', $value);
                    if (count($serial) > 1) {
                        if ($key == 0) {
                            $query->whereBetween($select, [$serial[0], $serial[1]]);
                        } else {
                            $query->orWhereBetween($select, [$serial[0], $serial[1]]);
                        }
                    } else {
                        if ($key == 0) {
                            $query->where($select, $serial[0]);
                        } else {
                            $query->orWhere($select, $serial[0]);
                        }
                    }
                }
            });
        } else {
            // no "." (dot) found in input
            // check "-" to separate serial range
            $serial = explode('-', $input);
            if (count($serial) > 1) {
                $query->whereBetween($select, [$serial[0], $serial[1]]);
            } else {
                $query->where($select, $serial[0]);
            }
        }

        return $query;
    }
}

if (!function_exists('serialConfig')) {
    function serialConfig($type = 'product', $bv = false, $prefix = null)
    {
        if ($bv) {
            $bvValue =  DB::table('sys_serial_config')->select('serial_config_bv as bv')->where('serial_config_prefix', '=', $prefix)->value('bv');
            return $bvValue;
        }

        $arr = array();
        $query = DB::table('sys_serial_config')->select(['serial_config_prefix as prefix', 'serial_config_bv as bv']);
        if ($type == 'product') {
            $query->where('serial_config_bv', '>', 0);
        }
        if ($query->count() > 0) {
            $arr = $query->get();
        }

        return $arr;
    }
}

if (!function_exists('querySort')) {
    function querySort($query, $sorts)
    {
        foreach ($sorts as $column => $dir) {
            $query->orderBy($column, $dir);
        }

        return $query;
    }
}

if (!function_exists('datetime_formated')) {
    function datetime_formated($date, $format = 'd/m/Y H:i:s')
    {
        if ($date != '0000-00-00 00:00:00' || !isNull($date)) {
            $date = Carbon\Carbon::parse($date)->translatedFormat($format);
            return $date;
        } else {
            return '-';
        }
    }
}

if (!function_exists('date_formated')) {
    function date_formated($date, $format = 'd F Y')
    {
        if ($date != '0000-00-00' || !isNull($date)) {
            $date = Carbon\Carbon::parse($date)->translatedFormat($format);
            return $date;
        } else {
            return '-';
        }
    }
}

if (!function_exists('rupiah')) {
    function rupiah($value, $decimal = 2)
    {
        $value = number_format($value, $decimal, ',', '.');
        return $value;
    }
}

if (!function_exists('areaName')) {
    function areaName($id)
    {
        $areaName = DB::table('ref_area')->where('area_id', $id)->value('area_name');
        return $areaName;
    }
}

if (!function_exists('countryName')) {
    function countryName($id)
    {
        $countryName = DB::table('sys_country')->where('country_id', $id)->value('country_name');
        return $countryName;
    }
}

if (!function_exists('getOne')) {
    function getOne($table, $column, $where, $sort = null, $dir = 'asc', $limit = null)
    {
        $query = DB::table($table)->select($column)->where($where);
        if (!empty($sort)) {
            $query->orderBy($sort, $dir);
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }

        $data = $query->value($column);

        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }
}

if (!function_exists('getId')) {
    function getId($mid)
    {
        $mid = DB::table('sys_network')->select('network_id')->where('network_mid', $mid)->value('network_id');
        return $mid;
    }
}

if (!function_exists('getMid')) {
    function getMid($id)
    {
        $mid = DB::table('sys_network')->select('network_mid')->where('network_id', $id)->value('network_mid');
        return $mid;
    }
}

if (!function_exists('getMemberName')) {
    function getMemberName($id)
    {
        $memberName = DB::table('sys_member')->select('member_name')->where('member_network_id', $id)->value('member_name');
        return $memberName;
    }
}
