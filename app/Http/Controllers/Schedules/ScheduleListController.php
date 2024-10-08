<?php

namespace App\Http\Controllers\Schedules;

use App\Domain\Schedules\Resources\ScheduleResource;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Auth;

class ScheduleListController extends Controller
{
    public function getScheduleListHemis(Req $request)
    {
        $data = collect();
        $current_week_start =Carbon::now()->startOfWeek()->timestamp;
        $current_week_end = Carbon::now()->endOfWeek()->timestamp;

        $request->validate([
            'group_id' => 'sometimes',
            'employee_id' => 'sometimes',
            'room_id' => 'sometimes',
        ]);
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . config('hemis.api_key'),
            'Accept' => 'application/json',
        ];
        $request_api = new Request(
            'GET',
            config('hemis.host') . 'data/schedule-list?limit=' . config('hemis.limit').'&lesson_date_from='.$current_week_start.'&lesson_date_to='.$current_week_end.'&_group=' . $request->group_id . '&_employee='.$request->employee_id.'&_auditorium='.$request->room_id,
            $headers
        );
        $res = $client->sendAsync($request_api)->wait();
        $res = $res->getBody();
        $result = json_decode($res);
//        dd($result->data);
        // Check if the request was successful
        if (isset($result->data->pagination->pageCount) && isset($result->data->items)) {
            // Add items from the first page
            foreach ($result->data->items as $dt) {
                $data->push($dt);
            }

            // Loop through the remaining pages
            for ($i = 2; $i <= $result->data->pagination->pageCount; $i++) {
                $request_api = new Request(
                    'GET',
                    config('hemis.host') . 'data/schedule-list?limit=' . config('hemis.limit').'&lesson_date_from='.$current_week_start.'&lesson_date_to='.$current_week_end.'&_group=' . $request->group_id . '&_employee='.$request->employee_id.'&_auditorium='.$request->room_id.'&page=' . $i,
                    $headers
                );
                $res = $client->sendAsync($request_api)->wait();
                $resBody = json_decode($res->getBody());

                // Check if the request was successful
                if (isset($resBody->data->items)) {
                    foreach ($resBody->data->items as $dt) {
                        $data->push($dt);
                    }
                }
            }
        }
        $data = $data->sortBy('lessonPair.start_time');
        return $this->successResponse('',ScheduleResource::collection($data));
    }

    public function userSchedule(Req $request)
    {
        $data = collect();
        $current_week_start =Carbon::now()->startOfWeek()->timestamp;
        $current_week_end = Carbon::now()->endOfWeek()->timestamp;

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . config('hemis.api_key'),
            'Accept' => 'application/json',
        ];
        $request_api = new Request(
            'GET',
            config('hemis.host') . 'data/schedule-list?limit=' . config('hemis.limit').'&lesson_date_from='.$current_week_start.'&lesson_date_to='.$current_week_end.'&_group=' . $request->group_id . '&_employee='.Auth::user()->id.'&_auditorium='.$request->room_code,
            $headers
        );
        $res = $client->sendAsync($request_api)->wait();
        $res = $res->getBody();
        $result = json_decode($res);
        // Check if the request was successful
        if (isset($result->data->pagination->pageCount) && isset($result->data->items)) {
            // Add items from the first page
            foreach ($result->data->items as $dt) {
                $data->push($dt);
            }

            // Loop through the remaining pages
            for ($i = 2; $i <= $result->data->pagination->pageCount; $i++) {
                $request_api = new Request(
                    'GET',
                    config('hemis.host') . 'data/schedule-list?limit=' . config('hemis.limit').'&lesson_date_from='.$current_week_start.'&lesson_date_to='.$current_week_end.'&_group=' . $request->group_id . '&_employee='.Auth::user()->id.'&_auditorium='.$request->room_code.'&page=' . $i,
                    $headers
                );
                $res = $client->sendAsync($request_api)->wait();
                $resBody = json_decode($res->getBody());

                // Check if the request was successful
                if (isset($resBody->data->items)) {
                    foreach ($resBody->data->items as $dt) {
                        $data->push($dt);
                    }
                }
            }
        }
        $data = $data->sortBy('lessonPair.start_time');
        return $this->successResponse('',ScheduleResource::collection($data));
    }

}
