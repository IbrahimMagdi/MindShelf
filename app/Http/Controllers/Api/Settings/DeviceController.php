<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Services\Settings\DeviceService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Settings\DeviceResource;


class DeviceController extends Controller
{
    public function __construct(protected DeviceService $service) {}
    public function index(Request $request)
    {
        $devices = $this->service->getDevices($request->user());

        return ApiResponse::success(DeviceResource::collection($devices['data']), 'Devices fetched successfully');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->service->logoutDevice($request->user(), $id);
            return ApiResponse::success(null, 'Device logged out successfully');
        } catch (\InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error('Device not found', 404);
        }
    }

    public function logoutOthers(Request $request)
    {
        $count = $this->service->logoutOtherDevices($request->user());
        return ApiResponse::success(['deleted_count' => $count], "Logged out from {$count} other devices");
    }

}
