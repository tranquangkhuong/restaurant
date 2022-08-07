<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

/**
 * Controller cơ bản cho API
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * status code
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * body response data
     *
     * @var Array
     */
    private $bodyResponse;

    /**
     * Addtion data
     *
     * @var
     */
    private $addition;

    /**
     * Get status code
     *
     * @return Int $statusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set status code
     *
     * @param Int $status
     */
    public function setStatusCode($status = 200)
    {
        $this->statusCode = $status;
    }

    /**
     * Set body and addtion data
     *
     * @param Array $response
     * @param Array $addtion
     */
    public function response($response = [], $addition = [], $message = '', $status = 200, $headers = [])
    {
        if ($status == 200) {
            $body['status'] = true;
            $body['message'] = $this->getMessage($message, true);
        } else {
            $body['status'] = false;
            $body['message'] = $this->getMessage($message, false);
        }
        $body['data'] = $response;
        if ($addition) {
            $body = array_merge($body, $addition);
        }
        // if (ApiDebug::check()) {
        //     $dbSytem = ApiDebug::getDbName("sqlsrv");
        //     $dbEcs = ApiDebug::getDbName("sqlsrvEcs");
        //     $body['db_log'][$dbSytem] = ApiDebug::getDbLog("sqlsrv");
        //     $body['db_log'][$dbEcs] = ApiDebug::getDbLog('sqlsrvEcs');
        // }
        return response()->json($body, $status, $headers);
    }

    private function getMessage($message, $success)
    {
        // if ($message == "") {
        //     $action = Route::getCurrentRoute()->getActionMethod();
        //     if ($success) {
        //         $key = "api.success: " . $action;
        //     } else {
        //         $key = "api.error: " . $action;
        //     }
        //     if (\Lang::has($key)) {
        //         $message = Lang::get($key);
        //     }
        // }
        return $message;
    }
}
