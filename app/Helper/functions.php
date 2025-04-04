<?php

use App\Helper\Status;
use App\Helper\API;
use App\Models\HumanResources\AdmLaborContract;
use App\Models\Prms\PrmsFormsRequiredFields;
use App\Models\Procurement\ProInspection;
use App\Models\User;
use App\Models\Com\GloEmployeeRegistry;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

if (!function_exists('rp_response')) {


    /**
     * Parse id to uuid
     */
    if (!function_exists('rp_id_to_uuid')) {
        function rp_id_to_uuid($model, $id): string|null
        {
            $query = app($model)->select('id', 'uuid')->where('id', $id);
            return $query->count() ? implode('', $query->get()->pluck('uuid')->toArray()) : null;
        }
    }
    function rp_response($data = [], $message = null, $status = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json([
                "messages" => __($message),
                "data" => $data,
                "status" => $status,
            ], $status > Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED ? Response::HTTP_SEE_OTHER : $status);
        } catch (\Exception $e) {
            return response()->json([
                "messages" => __("FailureProcess"),
                "data" => $e->getMessage(),
                "status" => $status
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}

if (!function_exists('rp_model_default')) {
    function rp_model_default($model, $lang = 'eng', $column = 'name'): string|array
    {
        $columns = config("rp.model.$model.columns.$lang");
        return (!is_null($columns) and count($columns))
            ? (is_null($column) ? $columns : $columns[array_search($column, $columns)]) : "name";
    }
}

if (!function_exists('get_model_by_table')) {
    function get_model_by_table($table): string|null
    {
        $model = null;
        if (\Illuminate\Support\Facades\File::isDirectory(app_path('Models'))) {
            $model_files = array_filter(\Illuminate\Support\Facades\File::allFiles(app_path('Models')), function ($file) {
                return $file->getExtension() === 'php';
            });
            foreach ($model_files as $model_file) {
                $model_class = str($model_file->getRealPath())->replace([base_path(), '/app', '.php', '/'], ['', 'App', '', '\\'])->toString();
                if (((app($model_class)->getTable() ?? null) == $table) and !str($model_class)->contains(['Recruitment']))
                    $model = $model_class;
            }
        }
        return $model;
    }
}

if (!function_exists('rp_get_env')) {
    function rp_get_env($filename = ' . env', $config = null): object
    {
        $data = collect();
        //Ibrahim kommente aldi (. env . test - faylini tapmirdi)
        //$path = base_path('.env.test');
        $content = file_get_contents(base_path($filename));
        foreach (explode("\n", $content) as $item) {
            if ($item and !str($item)->contains('#')) {
                $value = explode('=', $item);
                if (isset($value[0]) and isset($value[1])) {
                    //\r olduguna gore onu silmek ucun funskiya yazdim
                    $data->put($value[0], str_replace("\r", "", $value[1]));
                }
            }
        }

        return match ($config) {
            'DB' => (object)[
                'driver' => 'mysql',
                'connection' => data_get($data->all(), "DB_CONNECTION"),
                'host' => data_get($data->all(), "DB_HOST"),
                'port' => data_get($data->all(), "DB_PORT"),
                'database' => data_get($data->all(), "DB_DATABASE"),
                'username' => data_get($data->all(), "DB_USERNAME"),
                'password' => data_get($data->all(), "DB_PASSWORD"),
            ],
            'MAIL' => (object)[
                'mailer' => data_get($data->all(), "MAIL_MAILER"),
                'host' => data_get($data->all(), "MAIL_HOST"),
                'port' => data_get($data->all(), "MAIL_PORT"),
                'encryption' => data_get($data->all(), "MAIL_ENCRYPTION"),
                'username' => data_get($data->all(), "MAIL_USERNAME"),
                'password' => data_get($data->all(), "MAIL_PASSWORD"),
            ],
            default => (object)$data->all()
        };
    }
}

if (!function_exists('rp_user')) {
    function rp_user(bool $id = true)
    {
        return ($id) ? (request()->user()->id ?? rp_default_user($id)) : request()->user();
    }
}

if (!function_exists('rp_default_currency')) {
    function rp_default_currency(bool $id = true)
    {
        $data = \App\Models\Finance\CurrencyType::where('name', 'LIKE', " % " . config('rp.currency') . " % ")->where(['status' => Status::Active])->get()->first();

        if (is_null($data)) {
            throw new HttpResponseException(
                rp_response([], __("DefaultCurrencyData"), Response::HTTP_BAD_REQUEST)
            );
        } else
            return ($id) ? ($data->id ?? null) : $data;
    }
}

if (!function_exists('rp_employee')) {
    function rp_employee($id = null)
    {
        return GloEmployeeRegistry::where('user_id', empty($id) ? rp_user() : $id)->select('employee_id')->first()?->employee_id;

    }
}

if (!function_exists('rp_lc_structure_id')) {
    function rp_lc_structure_id($type = 'main'): int
    {
        try {
            return app('hr_adm_lc_structure')->where('key', config('rp.pl.hr_adm_lc_structure.default') ?? $type)->first()->id ?? -1;
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Artisan::call('cache:clear', ['--force']);
            return rp_lc_structure_id();
        }
    }
}

if (!function_exists('rp_user_jwt')) {
    function rp_user_jwt(bool $id = true)
    {
        return ($id) ? (auth()->setToken(request()->bearerToken())->user()->id ?? 0) : auth()->setToken(request()->bearerToken())->user();
    }
}

if (!function_exists('rp_array_to_string')) {
    function rp_array_to_string(array $data, bool $keys = false): string
    {
        return $keys ? implode(',', array_keys($data)) : implode(',', $data);
    }
}

if (!function_exists('rp_model_status_to_string')) {
    function rp_model_status_to_string($column): string
    {
        return __(Status::$messages[$column ?? 0]);
    }
}

if (!function_exists('rp_get_table')) {
    function rp_get_table($model): string|null
    {
        return $model ? app($model)->getTable() : null;
    }
}

if (!function_exists('rp_workers_or_person')) {
    function rp_workers_or_person(): array
    {
        count($user = AdmLaborContract::MyWorker(employee: rp_employee())->pluck('employee_id')->toarray()) == 0 ? $user[] = rp_employee() : array_push($user, rp_employee());

        return $user;
    }
}
/**
 * Parse key to id
 */
if (!function_exists('rp_key_to_id')) {
    function rp_key_to_id($model, $key): int|null
    {
        $query = app($model)->select('id', 'key')->where('key', $key);
        return $query->count() ? implode('', $query->get()->pluck('id')->toArray()) : null;
    }
}
/**
 * Parse uuid to id
 */
if (!function_exists('rp_uuid_to_id')) {
    function rp_uuid_to_id($model, $uuid): int|null
    {
        $query = app($model)->select('id', 'uuid')->whereNotNull('uuid')->where('uuid', $uuid);
        return $query->count() ? implode('', $query->get()->pluck('id')->toArray()) : null;
    }
}
/**
 * Parse uuid to key
 */
if (!function_exists('rp_uuid_to_key')) {
    function rp_uuid_to_key($model, $uuid): string|null
    {
        $query = app($model)->select('id', 'key')->whereNotNull('uuid')->where('uuid', $uuid);
        return $query->count() ? implode('', $query->get()->pluck('key')->toArray()) : null;
    }
}
/**
 * Parse id to uuid
 */
if (!function_exists('rp_id_uuid')) {
    function rp_id_uuid($model, $id): string|null
    {
        $query = app($model)->select('id', 'uuid')->where('id', $id);
        return $query->count() ? implode('', $query->get()->pluck('uuid')->toArray()) : null;
    }
}
/**
 * Parse uuids to ids
 */
if (!function_exists('rp_uuid_to_ids')) {
    function rp_uuid_to_ids($model, $uuids): array
    {
        $query = app($model)->select('id', 'uuid')->whereIn('uuid', $uuids);
        return $query->count() ? $query->get()->pluck('id')->toArray() : [];
    }
}




if (!function_exists('rp_tabel_to_model')) {
    function rp_tabel_to_model($model, $uuid): string|null
    {
        $query = app($model)->select('model_name')->where('uuid', $uuid);

        return $query->count() ? implode('', $query->get()->pluck('model_name')->toArray()) : null;
    }
}

if (!function_exists('is_base64')) {
    function is_base64($string): bool
    {
        return preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string) and (base64_encode(base64_decode($string, true)) === $string);
    }
}

if (!function_exists('timestamp_convert_date')) {
    function timestamp_convert_date($date)
    {
        return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->toDateString();
    }
}

if (!function_exists('rp_get_browser')) {
    function rp_get_browser($request): string
    {
        $agent = new \Jenssegers\Agent\Agent;
        $data = array_filter([
            (string)($agent->platform() . (int)$agent->version($agent->platform())),
            (string)$agent->browser(), $agent->robot(),
            "IP:{$request->getClientIp(true)}"
        ]);
        return implode(', ', $data);
    }
}

if (!function_exists('rp_default_user')) {
    function rp_default_user($id = true)
    {
        if ($id) {
            return User::where('email', config('rp.default_user.email'))->select('id')->first()->id ?? User::create(config('rp.default_user'))->id;
        } else {
            return User::where('email', config('rp.default_user.email'))->select('id')->first() ?? User::create(config('rp.default_user'));
        }

    }
}

if (!function_exists('timestamp_convert_time')) {
    function timestamp_convert_time($date)
    {
        return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->toTimeString();
    }
}

if (!function_exists('rp_xml_to_json')) {
    function rp_xml_to_json($file = null, $content = null, $option = false)
    {
        return is_null($content)
            ? json_decode(json_encode(json_decode(json_encode(simplexml_load_file(file_get_contents(public_path($file)))))))
            : (($option)
                ? json_decode(json_encode(json_decode(json_encode(simplexml_load_string(($content))))))
                : json_decode(json_encode(json_decode(json_encode(simplexml_load_string(file_get_contents($content), "SimpleXMLElement", LIBXML_NOCDATA))))));
    }
}

if (!function_exists('rp_log')) {
    function rp_log($data, $file = "rp_log", $type = "info", $driver = 'daily')
    {
        Log::build(['driver' => $driver, 'path' => storage_path("logs / $file . log")])->{$type}(json_encode($data));
    }
}

if (!function_exists('rp_data_security')) {
    function rp_data_security($data, $cipher_algo = "AES-256-CBC", $options = 0)
    {
        return openssl_encrypt(json_encode($data), $cipher_algo, config('app.encryption_key'), $options, config('app.encryption_secret'));
    }
}

if (!function_exists('rp_data_security_decryption')) {
    function rp_data_security_decryption($encrypted, $cipher_algo = "AES-256-CBC", $options = 0)
    {
        return json_decode(openssl_decrypt($encrypted, $cipher_algo, config('app.encryption_key'), $options, config('app.encryption_secret')));
    }
}

if (!function_exists('json_validate')) {
    function json_validate($string): bool|string
    {
        json_decode($string);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = __('The maximum stack depth has been exceeded.');
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = __('Invalid or malformed JSON.');
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = __('Control character error, possibly incorrectly encoded.');
                break;
            case JSON_ERROR_SYNTAX:
                $error = __('Syntax error, malformed JSON.');
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = __('Malformed UTF-8 characters, possibly incorrectly encoded.');
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = __('One or more recursive references in the value to be encoded.');
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = __('One or more NAN or INF values in the value to be encoded.');
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = __('A value of a type that cannot be encoded was given.');
                break;
            default:
                $error = __('Unknown JSON error occured.');
                break;
        }

        if ($error !== '') {
            return $error;
        }
        return true;
    }
}

if (!function_exists('rp_delete_not_in')) {
    function rp_delete_not_in($model, array $columns, $coming)
    {
        return app($model)::where($columns)->whereNotIn('id', $coming)->deactive();
    }
}

if (!function_exists('rp_fillable_for_update')) {
    function rp_fillable_for_update($model, $disabled_data)
    {
        $columns = app($model)->getFillable();
        if (!is_null($disabled_data) and count($disabled_data)) {
            foreach ($disabled_data->where('model', $model) as $key) {
                unset($columns[array_search($key->field, $columns)]);
            }
        }

        return array_values($columns);
    }
}

if (!function_exists('rp_fillable')) {
    function rp_fillable($model,$data,$disabled = null)
    {
        $columns = app($model)->getFillable();
        if (!is_null($disabled) and count($disabled)) {
            foreach ($disabled->where('model', $model) as $key) {
                unset($columns[array_search($key->field, $columns)]);
            }
        }
      return  collect($data)->only(array_values($columns))->toArray();
    }
}

if (!function_exists('rp_rule_add_required')) {
    function rp_rule_add_required($rule, $request, $trn_id)
    {
        $data = PrmsFormsRequiredFields::FildAndPath($trn_id)
            ->where('prms_forms_required_fields.status', Status::Active)
            ->select('sgnt_pl_fields.name as field', 'sgnt_pl_path.name as path', 'prms_forms_required_fields.value')->status()->get();

        foreach ($data as $item) {
//            $rule[$item ->path . $item ->field][0] = rp_required_field_check($request, $item->value);
            $rule[(empty($item->path) ? null : $item->path . '.') . $item->field][0] = rp_required_field_check($request, $item->value);
        }

        return $rule;
    }
}


if (!function_exists('rp_rule_add_required_for_store')) {
    function rp_rule_add_required_for_store($rule, $request, $form_key)
    {
        $data = PrmsFormsRequiredFields::FildAndPathForFormAndStatus($form_key)
            ->where('prms_forms_required_fields.status', Status::Active)
            ->select('sgnt_pl_fields.name as field', 'sgnt_pl_path.name as path', 'prms_forms_required_fields.value')->status()->get();

        foreach ($data as $item) {
            $rule[(empty($item->path) ? null : $item->path . '.') . $item->field][0] = rp_required_field_check($request, $item->value);
        }

        return $rule;
    }
}

if (!function_exists('rp_rule_add_required_for_will_status')) {
    function rp_rule_add_required_for_will_status($rule, $request, $status_id)
    {
        $data = PrmsFormsRequiredFields::FildAndPathForStatus(is_array($request) ? $request['trn_id'] : $request->trn_id, $status_id)
            ->where('prms_forms_required_fields.status', Status::Active)
            ->select('sgnt_pl_fields.name as field', 'sgnt_pl_path.name as path', 'prms_forms_required_fields.value')->status()->get();
        foreach ($data as $item) {
            $rule[(empty($item->path) ? null : $item->path . '.') . $item->field][0] = rp_required_field_check($request, $item->value);
        }
        return $rule;
    }
}

if (!function_exists('rp_required_field_check')) {
    function rp_required_field_check($request, $value)
    {

        $request = is_array($request) ? $request : $request->toArray();
        $return = 'nullable';
        if ($value == null) {
            $return = 'required';
        } else {
            $abb = explode(':', $value);
            if (($abb[0] ?? null) == 'rp_if') {
                $data = explode(',', $abb[1]);

                if (isset($data[1])) {
                    if ($data[1] == '=') {
                        if ($request[$data['0']] == ($data['2'] == 'null' ? null : $data['2']) ?? null) {
                            $return = 'required';
                        }
                    }
                }
            } else {
                $return = $value;
            }
        }

        return $return;
    }
}

if (!function_exists('rp_currency')) {
    function rp_currency($currency_id, $date = null)
    {
        $currency = new API();

        $data = $currency->currency($currency_id, $date);
        return $data;
    }
}

if (!function_exists('rp_mb_ucfirst')) {
    function rp_mb_ucfirst($string, $encoding = null)
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}

if (!function_exists('rp_camel_keys')) {
    /**
     * Convert array keys to camel case recursively.
     *
     * @param array $array
     * @return array
     */
    function rp_camel_keys(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = rp_camel_keys($value);
            }
            $result[str($key)->camel()->toString()] = $value;
        }
        return $result;
    }
}

if (!function_exists('rp_snake_keys')) {
    /**
     * Convert array keys to snake case recursively.
     *
     * @param array $array
     * @param string $delimiter
     * @return array
     */
    function rp_snake_keys(array $array, string $delimiter = '_'): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = rp_snake_keys($value, $delimiter);
            }
            $result[str($key)->snake($delimiter)->toString()] = $value;
        }
        return $result;
    }

    function rp_convert_file_name_with_date(string $file_key, string $type = 'excel', string $name = null)
    {
        $type_ext = match ($type) {
            'excel' => 'xlsx',
            default => null,
        };
        return config($file_key) . (Carbon::now())->format('Y-m-d H:i:s') . "." . ($name ?? $type_ext);
    }

    function rp_get_route_name(string $name = null): string|null
    {
        if (is_null($name)) {
            $result = null;
        }else if(str_contains($name,'.')){
            $arr = explode('.', $name)[1] ?? null;
            $result = strlen($arr) == 0 ? null : $arr;
        }else{
            $result = $name;
        }
        return $result;
    }
}


if (!function_exists('rp_mb_ucfirst')) {
    function rp_array_to_string_($value)
    {
        $sign = ',';
        $string = '';
        foreach (config('rp.pratocol_status') as $value){
            $string  .= __($value)  . $sign;
        }
        return rtrim($string,',');
    }
}

if (!function_exists('rp_array_string_whith_lang')) {
    function rp_array_string_whith_lang($array,$sign=',')
    {
        $string = '';
        foreach ($array as $value){
            $string  .= __($value)  . $sign;
        }
        return rtrim($string,',');
    }
}
if (!function_exists('rp_get_all_table')) {
    function rp_get_all_table()
    {
        $departments_list = File::directories(app_path('Models'));

        $table_list = [];
        foreach ($departments_list as $department_list) {
            $modelFiles = File::files($department_list ?? null);
            $drection_name = explode("Models", $department_list)[1];

            $namespaces = [];

            foreach ($modelFiles as $file) {
                // Dosyanın tam adını al
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $namespace = 'App\\Models' . $drection_name . '\\' . $filename;

                // Dizine ekle
                $namespaces[] = $namespace;
            }

            foreach ($namespaces as $item) {
                $table_list [trim($drection_name, '\\')][] = (app($item) ?? null)->getTable();
            }
        }
        return $table_list;
    }
}
if (!function_exists('rp_update_many_relation')) {
    function rp_update_many_relation($model, $data, $relation, $checkColumn, $deactiveOtherData = true)
    {
        $ids = [];
        foreach ($data as $item) {
            $ids[] = $model->$relation()->updateOrCreate([$checkColumn => $item[$checkColumn]], $item)->id;
        }
        if($deactiveOtherData)
            $model->$relation()->whereNotIn('id', $ids)->deactive();
    }
}

if (!function_exists('rp_sentence_ucfirst')) {
    function rp_sentence_ucfirst($sentence)
    {
        $result = null;
        foreach (explode(' ',$sentence) as $item){
            $result .= ucfirst($item).' ';
        }
        return $result;
    }
}

if (!function_exists('rp_make_unique_key')) {
    function rp_make_unique_key($name, $model, $column_name = 'key')
    {
        $key = str()->slug($name, '_');
        $count = 1;
        while (app($model)->where($column_name, $key)->exists()) {
            $key = str()->slug($name . '_' . $count++, '_');
        }
        return $key;
    }
}

if (!function_exists('rp_make_data_order')) {
    function rp_make_data_order($model, $params, $order_column, $min, $max): float|int
    {
        if (is_null($max) || $max == 0) {
            $max_order = $model->when(is_array($params), function ($q) use ($params) {
                $q->where($params);
            })->max($order_column) ?? 0;
            $res = $max_order+1;
        } else {
            $res = findLargestDecimal($min, $max, 10);
        }

        return $res;
    }
}

if(!function_exists('findLargestDecimal')) {
    function findLargestDecimal($min, $max, $decimalPlaces): float
    {
        $largestDecimal = $max - pow(10, -$decimalPlaces);

        if ($largestDecimal < $min) {
            $largestDecimal = $min;
        }

        return floatval(number_format($largestDecimal, $decimalPlaces, '.', ''));
    }
}



if (!function_exists('string_to_array')) {
    function string_to_array($string)
    {
        $string = trim($string, '[] ');
        return array_filter(array_map('trim', explode(',', $string)), function ($value) {
            return $value !== ''; // Remove empty values
        });
    }
}
