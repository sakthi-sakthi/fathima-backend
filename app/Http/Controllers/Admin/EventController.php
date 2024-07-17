<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
       
    }
    public function create()
    {
        $event = '';
        return view('admin.events.create',compact('event'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'startdate' => 'required',
            'recurring' => 'nullable',
            'firstfriday' => 'nullable',
        ]);

        $startdate = Carbon::createFromFormat('m/d/Y h:i a', $validatedData['startdate']);
        $recurring = $request->has('recurring') && $request->recurring == 'on';
        $firstFriday = $request->has('firstfriday') && $request->firstfriday == 'on';

        $event = new Event();
        $event->startdate = $startdate;
        $event->recurring = $recurring;
        $event->first_friday = $firstFriday;
        $event->save();

        // If it's a recurring event, create events for each week
        if ($recurring) {
            $currentDate = $startdate->copy();

            while (true) {
                $currentDate->addWeek();
                if ($currentDate->year != Carbon::now()->year) break;

                $newStartDate = $currentDate->copy();

                if ($newStartDate->dayOfWeek == $startdate->dayOfWeek) {
                    $recurringEvent = new Event();
                    $recurringEvent->startdate = $newStartDate->format('Y-m-d H:i:s');
                    $recurringEvent->recurring = true;
                    $recurringEvent->save();
                }
            }
        }

        // If it's a special mass (first Friday), create events for each first Friday in entire year
        if ($firstFriday) {
            $currentDate = $startdate->copy()->startOfYear();
            $endDate = $startdate->copy()->endOfYear();

            while ($currentDate->lte($endDate)) {
                $firstFridayOfMonth = $currentDate->copy()->firstOfMonth(Carbon::FRIDAY);

                // Check if current date is in the same month as the start date
                if ($firstFridayOfMonth->month == $startdate->month) {
                    // Check if there is already a special event in the same month
                    $existingSpecialEvent = Event::where('startdate', '=', $firstFridayOfMonth->copy()->setTime($startdate->hour, $startdate->minute, $startdate->second))->where('first_friday', '=', true)->first();

                    if (!$existingSpecialEvent) {
                        $specialEvent = new Event();
                        $specialEvent->startdate = $firstFridayOfMonth->copy()->setTime($startdate->hour, $startdate->minute, $startdate->second);
                        $specialEvent->recurring = false;
                        $specialEvent->first_friday = true;
                        $specialEvent->save();
                    }
                } else {
                    $specialEvent = new Event();
                    $specialEvent->startdate = $firstFridayOfMonth->copy()->setTime($startdate->hour, $startdate->minute, $startdate->second);
                    $specialEvent->recurring = false;
                    $specialEvent->first_friday = true;
                    $specialEvent->save();
                }

                $currentDate->addMonth();
            }
        }

        return redirect()->back()->with('success', 'Event saved successfully!');
    }

    public function show($id)
    {
       
    }
    public function edit($id)
    {
        $event = Event::find($id);

        if ($event) {
            return response()->json(['status' => 'success', 'data' => $event]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Event not found!'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'startdate' => 'required',
            'recurring' => 'nullable',
        ]);

        try {
            $startdate = Carbon::createFromFormat('m/d/Y h:i a', $validatedData['startdate']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Invalid date format!'], 422);
        }

        $event = Event::find($id);

        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found!'], 404);
        }

        $event->startdate = $startdate;
        $event->recurring = $request->has('recurring') && $request->recurring == 'on' ? 1 : 0;

        $event->save();

        if ($event->recurring) {
            $currentDate = Carbon::createFromFormat('m/d/Y h:i a', $validatedData['startdate']);
    
            while (true) {
                $currentDate->addWeek();
                if ($currentDate->year != Carbon::now()->year) break;
    
                $newStartDate = $currentDate->copy();
    
                if($newStartDate->dayOfWeek == $startdate->dayOfWeek){
                    $recurringEvent = Event::where('startdate',$newStartDate->format('Y-m-d H:i:s'))->first();
                    if(!$recurringEvent){
                        $recurringEvent = new Event();
                        $recurringEvent->startdate = $newStartDate->format('Y-m-d H:i:s');
                        $recurringEvent->recurring = $event->recurring;
                        $recurringEvent->save();
                    }
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Event updated successfully.']);
    }


    public function destroy($id)
    {
        $event = Event::find($id);

        if ($event) {
            $event->delete();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Event not found!'], 404);
        }
    }

}


// when select recurring option it display each month that selected date showing the event but now instaed of date each month day wise show the event example when i add mass time for friday and select recurring each friday show that event not only month wise each friday show that event