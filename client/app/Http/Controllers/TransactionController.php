<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function index()
    {
        // return view('login', [
        //     'title' => 'Login'
        // ]);
    }

    public function withdraw()
    {
        return view('withdraw', [
            'title' => 'Withdraw',
            'breadcrumbs' => [
                'Withdraw' => [
                    'icon' => 'payments',
                    'link' => 'payments'
                ]
            ]
        ]);
    }

    public function deposit()
    {
        return view('deposit', [
            'title' => 'Deposit',
            'breadcrumbs' => [
                'Deposit' => [
                    'icon' => 'payments',
                    'link' => 'payments'
                ]
            ]
        ]);
    }

    public function history()
    {
        return view('history', [
            'title' => 'History',
            'breadcrumbs' => [
                'History' => [
                    'icon' => 'history',
                    'link' => 'history'
                ]
            ]
        ]);
    }

    public function getBalance()
    {
        $response = $this->fetchGetBalance();
        if($response->code == 401 || $response->code == 403) {
            session()->invalidate();

            session()->regenerateToken();

            return response()->json($response, 401);
        }
        return response()->json($response, 200);
    }

    public function getHistory()
    {
        $response = $this->fetchGetHistory();
        if($response->code == 401 || $response->code == 403) {
            session()->invalidate();

            session()->regenerateToken();

            return response()->json($response, 401);
        }
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            "amount" => ["required"],
            "type" => ["required", "in:withdraw,deposit"]
        ]);

        $validate['amount'] = str_replace('.', '', $validate['amount']);
        $validate['amount'] = (float)str_replace(',', '.', $validate['amount']);
        $validate['order_id'] = (string)rand(1, 999999);
        $validate['timestamp'] = date('Y-m-d H:i:s');

        if ($validate['type'] == 'withdraw') {
            $response = $this->fetchWithdrawApi($validate);
        } else {
            $response = $this->fetchDepositApi($validate);
        }
        if($response->code == 401 || $response->code == 403) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json($response, 401);
        }

        if ($response->status) {
            return response()->json($response, 200);
        } else {
            return response()->json($response, 200);
        }
    }


    private function fetchWithdrawApi($data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token'),
            ])->withBody(
                json_encode($data)
            )->post('http://127.0.0.1:3000/transaction/withdraw');
            $results = json_decode($response->body());
            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function fetchDepositApi($data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token'),
            ])->withBody(
                json_encode($data)
            )->post('http://127.0.0.1:3000/transaction/deposit');
            $results = json_decode($response->body());
            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function fetchGetBalance()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token'),
            ])->get('http://127.0.0.1:3000/wallet');
            $results = json_decode($response->body());
            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function fetchGetHistory()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token'),
            ])->get('http://127.0.0.1:3000/transaction/history');
            $results = json_decode($response->body());
            return $results;
            dd($results);
        } catch (Exception $e) {
            return $e;
        }
    }
}
