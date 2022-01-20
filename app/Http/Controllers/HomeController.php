<?php

namespace App\Http\Controllers;

use App\Notifications\MyFirstNotification;
use App\User;
use Illuminate\Http\Request;
use Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        remove extra commits
        return view('home');
    }

    public function sendNotification()
    {
        $user = User::first();
        $details = [
            'greeting' => 'Hi Artisan',
            'body' => 'This is my first notification from ItSolutionStuff.com',
            'thanks' => 'Thank you for using ItSolutionStuff.com tuto!',
            'actionText' => 'View My Site',
            'actionURL' => url('/'),
            'order_id' => 101
        ];

        Notification::send($user, new MyFirstNotification($details));

        dd('done');
    }

    public function downloadDailyStatementWorking(Request $request)
    {
        /*post data => currentDate: "2021-12-31T06:05:04.285Z"
                    endDate: "2021-11-02"
                    isStatement: true
                    merchants: ["Besoft"]
                    startDate: "2021-10-29"*/

        /*updated start*/
//        $data = NewUpdatedTrx::select('date')->whereBetween('date', [$request['startDate'], $request['endDate']])->distinct()->get();
//        return $data;
        /*foreach($dates as $date)
        {
            $date = $date->date;
        }*/
        /*updated end*/

        $dates = \DB::table('cp_trx')->select('date')->whereBetween('date', [$request['startDate'], $request['endDate']])->distinct()->get();

        $merchantNamesArray = $request['merchants'];

        foreach ($dates as $date) {
            $date = $date->date;

            // $request['merchants'] => 0: "Besoft"
            // $merchantNamesArray => 0: {merchant_name: "Besoft"}

            if ($request['isStatement'] == true) {
                foreach ($merchantNamesArray as $merchantName) {

                    // $merchantName => {merchant_name: "Besoft"};
                    $volumes = \App\Http\Controllers\CalculateVolumesController::calculateVolumes($merchantName, $date, 'cp_trx');
                    /*$volumes => merchant_Name: {merchant_name: "Besoft"}
                                sold_EUR_EEA_Volume: 0
                                sold_EUR_NEEA_Volume: 0
                                sold_NEUR_EEA_Volume: 43.8165
                                sold_NEUR_NEEA_Volume: 21684.838499999998
                                total_Trx_Count: 594
                                total_volume: 21728.655*/

//                    return $volumes;


//                    $before_volumes = \App\Http\Controllers\CalculateVolumesController::volumesWithoutDeduction($merchantName, $date, $date, 'cp_trx');
                    /*before volumes = merchant_Name: "Besoft"
                                    sold_EUR_EEA_Volume: 0
                                    sold_EUR_NEEA_Volume: 0
                                    sold_NEUR_EEA_Volume: 44.94
                                    sold_NEUR_NEEA_Volume: 22240.859999999997
                                    total_volume: 22285.799999999996*/


//                    return $before_volumes;
                    $statementData = \App\Http\Controllers\CalculateStatementsController::calculateStatementData($volumes['withDeduction'], $merchantName, $request['startDate'], $request['endDate'], $date, 'cp_trx', $volumes['withoutDeduction']);
                    return $statementData;
                    $data = \App\Http\Controllers\Statement\PDFController::generatePDF($statementData['statement_data'], $statementData['merchant_rates_and_codes'], 'Daily', $request['isStatement']);
                    $dateName = $request['startDate'] == $request['endDate'] ? $request['startDate'] : $request['startDate'] . '_' . $request['endDate'];
                    $pdfNames = $data['internalCode'] . '_' . $data['brandCode'] . '_' . $dateName;
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf = PDF::loadView('statementPDF', compact('data'));
                    Storage::put('public/pdf' . '_' . $request['currentDate'] . '/DailyPdf' . '_' . $request['startDate'] . '_' . $request['endDate'] . '/' . $pdfNames . '.pdf', $pdf->output());
                }
                array_push($this->path, storage_path('app/public/pdf' . '_' . $request['currentDate'] . '/DailyPdf' . '_' . $request['startDate'] . '_' . $request['endDate']));
            }
        }




        $message = ' has requested to Download Daily PDF Statement for date ' . $this->date;
        LogsController::updateLogs($request, $message, 'Daily Statement');
        $pathId = filePath::storePaths($request->startDate, $request->endDate, 'Daily_Statement.zip');
        $request->request->add(['pathId' => $pathId]); //add request
        generateDailyStatement::dispatch($request->all(), Auth::user());
        sleep(2);
        return "Downloading....";
    }

    public function downloadDailyStatement(Request $request)
    {
//        previous
        /*$dates = \DB::table('cp_trx')->select('date')->whereBetween('date', [$request['startDate'], $request['endDate']])->distinct()->get();
        foreach ($dates as $date) {
            $date = $date->date;
            $request['startDate'] = $date;
            $request['endDate'] = $date;
            foreach ($request['merchants'] as $merchantName) {
                $merchantNamesArray[] = \DB::table('statement_merchant_codes_and_rates')->select('merchant_name')->where('is_draft', 0)
                    ->where('merchant_name', $merchantName)->first();
            }
            if ($request['isStatement'] == true) {
                foreach ($merchantNamesArray as $merchantName) {
                    $merchantName = (array) $merchantName;
                    $volumes = \App\Http\Controllers\CalculateVolumesController::calculateVolumes($merchantName, $request['startDate'], $request['endDate'], 'cp_trx');
                    $before_volumes = \App\Http\Controllers\CalculateVolumesController::volumesWithoutDeduction($merchantName, $request['startDate'], $request['endDate'], 'cp_trx');
                    $statementData = \App\Http\Controllers\CalculateStatementsController::calculateStatementData($volumes, $merchantName, $request['startDate'], $request['endDate'], $date, 'cp_trx', $before_volumes);
//                    return $statementData;

                    $data = \App\Http\Controllers\Statement\PDFController::generatePDF($statementData['statement_data'], $statementData['merchant_rates_and_codes'], 'Daily', $request['isStatement']);
                    $dateName = $request['startDate'] == $request['endDate'] ? $request['startDate'] : $request['startDate'] . '_' . $request['endDate'];
                    $pdfNames = $data['internalCode'] . '_' . $data['brandCode'] . '_' . $dateName;
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf = PDF::loadView('statementPDF', compact('data'));
                    Storage::put('public/pdf' . '_' . $request['currentDate'] . '/DailyPdf' . '_' . $request['startDate'] . '_' . $request['endDate'] . '/' . $pdfNames . '.pdf', $pdf->output());
                }
                array_push($this->path, storage_path('app/public/pdf' . '_' . $request['currentDate'] . '/DailyPdf' . '_' . $request['startDate'] . '_' . $request['endDate']));
            }
        }*/
//        previous

//        updated
        $dates = \DB::table('cp_trx')->select('date')->whereBetween('date', [$request['startDate'], $request['endDate']])->distinct()->get();
        $merchantNamesArray = $request['merchants'];
        foreach ($dates as $date) {
            $date = $date->date;
            $request['startDate'] = $date;
            $request['endDate'] = $date;
            if ($request['isStatement'] == true) {
                foreach ($merchantNamesArray as $merchantName) {
                    $volumes = \App\Http\Controllers\CalculateVolumesController::calculateVolumesUpdated($merchantName, $date, 'cp_trx');
                    $statementData = \App\Http\Controllers\CalculateStatementsController::calculateStatementData($volumes['withDeduction'], $merchantName, $request['startDate'], $request['endDate'], $date, 'cp_trx', $volumes['withoutDeduction']);
//                    return $statementData;
                    $data = \App\Http\Controllers\Statement\PDFController::generatePDF($statementData['statement_data'], $statementData['merchant_rates_and_codes'], 'Daily', $request['isStatement']);

                    $dateName = $request['startDate'] == $request['endDate'] ? $request['startDate'] : $request['startDate'] . '_' . $request['endDate'];
                    $pdfNames = $data['internalCode'] . '_' . $data['brandCode'] . '_' . $dateName;
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf = PDF::loadView('statementPDF', compact('data'));
                    Storage::put('public/pdf' . '_' . $request['currentDate'] . '/DailyPdf' . '_' . $request['startDate'] . '_' . $request['endDate'] . '/' . $pdfNames . '.pdf', $pdf->output());
                }
                array_push($this->path, storage_path('app/public/pdf' . '_' . $request['currentDate'] . '/DailyPdf' . '_' . $request['startDate'] . '_' . $request['endDate']));
            }
        }


        return 'done';
        $message = ' has requested to Download Daily PDF Statement for date ' . $this->date;
        LogsController::updateLogs($request, $message, 'Daily Statement');
        $pathId = filePath::storePaths($request->startDate, $request->endDate, 'Daily_Statement.zip');
        $request->request->add(['pathId' => $pathId]); //add request
        generateDailyStatement::dispatch($request->all(), Auth::user());
        sleep(2);
        return "Downloading....";
    }

    public static function calculateVolumes($merchant_name, $date, $trx_table)
    {
        try {

            $EuorpianCountries = [
                'BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'IE', 'EL', 'ES', 'FR', 'HR',
                'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL', 'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI',
                'SE', 'IS', 'NO', 'LI', 'CH',
            ];
            /*$sold_NEUR_NEEA_Volume = DB::table($trx_table)->where('merchant_name', '=', $merchant_name)->whereBetween('date', [$startDate, $endDate])->where('status' ,'!=', 'refunded')->where('fiat_currency', '!=', 'EUR')->whereNotIn('country', $EuorpianCountries)->sum('settled_amount') * 0.975;
            return $sold_NEUR_NEEA_Volume => 21684.8385;*/
            $res = DB::table($trx_table)->where('merchant_name', '=', $merchant_name)->whereBetween('date', [$date, $date])->get();

            $sold_EUR_EEA_Volume = $res->where('status' ,'!=', 'refunded')->where('fiat_currency', '=', 'EUR')->whereIn('country', $EuorpianCountries)->sum('settled_amount');
            $sold_EUR_NEEA_Volume = $res->where('status' ,'!=', 'refunded')->where('fiat_currency', '=', 'EUR')->whereNotIn('country', $EuorpianCountries)->sum('settled_amount');
            $sold_NEUR_EEA_Volume = $res->where('status' ,'!=', 'refunded')->where('fiat_currency', '!=', 'EUR')->whereIn('country', $EuorpianCountries)->sum('settled_amount');
            $sold_NEUR_NEEA_Volume = $res->where('status' ,'!=', 'refunded')->where('fiat_currency', '!=', 'EUR')->whereNotIn('country', $EuorpianCountries)->sum('settled_amount');
            $total_Trx_Count = $res->count();
            return [
                'withDeduction' => [
                    'merchant_Name' => $merchant_name,
                    'total_Trx_Count' => $total_Trx_Count,
                    'sold_EUR_EEA_Volume' => $sold_EUR_EEA_Volume * 0.975,
                    'sold_EUR_NEEA_Volume' => $sold_EUR_NEEA_Volume * 0.975,
                    'sold_NEUR_EEA_Volume' => $sold_NEUR_EEA_Volume * 0.975,
                    'sold_NEUR_NEEA_Volume' => $sold_NEUR_NEEA_Volume * 0.975,
                    'total_volume' => ($sold_EUR_EEA_Volume + $sold_EUR_NEEA_Volume + $sold_NEUR_EEA_Volume + $sold_NEUR_NEEA_Volume) * 0.975

                ],
                'withoutDeduction' => [
                    'sold_EUR_EEA_Volume' => $sold_EUR_EEA_Volume,
                    'sold_EUR_NEEA_Volume' => $sold_EUR_NEEA_Volume,
                    'sold_NEUR_EEA_Volume' => $sold_NEUR_EEA_Volume,
                    'sold_NEUR_NEEA_Volume' => $sold_NEUR_NEEA_Volume,
                    'total_volume' => ($sold_EUR_EEA_Volume + $sold_EUR_NEEA_Volume + $sold_NEUR_EEA_Volume + $sold_NEUR_NEEA_Volume)
                ]

            ];
        } catch (\Exception $e) {
            \App\Models\ErrorLog::insert(['error_message' => $e->getMessage(), 'line_number' => $e->getLine(), 'method' => 'calculateVolumes']);
            return response()->json(['error' => "true", 'message' => "Error Occured"]);
        }
    }
}
