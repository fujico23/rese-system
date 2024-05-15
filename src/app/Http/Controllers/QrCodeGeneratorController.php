<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGeneratorController extends Controller
{
    public function index()
    {
        $qrCode = QrCode::format('png')
        ->size(200)
        ->generate('http://localhost/mypage');

        return view ('qr_code', compact('qrCode'));
    }
    //
}
