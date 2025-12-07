<?php

namespace App\Http\Controllers;

use DOMXPath;
use Exception;
use DOMDocument;
use SimpleXMLElement;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(): View {
        return view('dashboard');
    }
    
    public function testGetData(){

        $xmlString = file_get_contents(public_path('response.xml'));

        $parsed = $this->parseAccessLog($xmlString);

        dd($parsed);

    }
  public function parseAccessLog($xmlString)
    {
    // 1. Muat string XML ke DOMDocument dan nonaktifkan error/warning
        $dom = new DOMDocument();
        // Gunakan @ untuk menekan warning SimpleXML/DOM,
        // terutama yang terkait dengan namespace non-absolute.
        @$dom->loadXML($xmlString);

        // 2. Buat objek DOMXPath
        $xpath = new DOMXPath($dom);

        // 3. Daftarkan namespace SOAP agar dapat memulai query
        // Kita perlu namespace SOAP karena tag terluar adalah <soap:Envelope>
        $xpath->registerNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');

        // 4. Buat query XPath yang kuat.
        // Kita gunakan s:Body untuk navigasi awal, lalu *[local-name()='...']
        // untuk mengabaikan semua namespace yang bermasalah (WebAPI, InOut, diffgr, dll.)
        
        $query = '//s:Body/*[local-name()="GetTransactionResponse"]/*[local-name()="GetTransactionResult"]/*[local-name()="diffgram"]/*[local-name()="DocumentElement"]/*[local-name()="InOut"]';

        // 5. Eksekusi query untuk mendapatkan semua node InOut
        $inOutNodes = $xpath->query($query);

        $data = [];

        // 6. Loop dan Ekstrak Data
        foreach ($inOutNodes as $node) {
            // Karena data di dalam <InOut> juga memiliki namespace (xmlns="InOut"),
            // kita perlu menggunakan local-name() atau inisialisasi namespace InOut.
            // Cara termudah adalah menggunakan local-name() relatif ke node InOut.
            
            $data[] = [
                'TrDate'      => $xpath->query('./*[local-name()="TrDate"]', $node)->item(0)->nodeValue ?? null,
                'TrTime'      => $xpath->query('./*[local-name()="TrTime"]', $node)->item(0)->nodeValue ?? null,
                'CardNo'      => $xpath->query('./*[local-name()="CardNo"]', $node)->item(0)->nodeValue ?? null,
                'Transaction' => $xpath->query('./*[local-name()="Transaction"]', $node)->item(0)->nodeValue ?? null,
                'TrCode'      => $xpath->query('./*[local-name()="TrCode"]', $node)->item(0)->nodeValue ?? null,
                'DoorName'    => $xpath->query('./*[local-name()="DoorName"]', $node)->item(0)->nodeValue ?? null,
                'CardName'    => $xpath->query('./*[local-name()="CardName"]', $node)->item(0)->nodeValue ?? null,
                'Department'  => $xpath->query('./*[local-name()="Department"]', $node)->item(0)->nodeValue ?? null,
                'StaffNo'     => $xpath->query('./*[local-name()="StaffNo"]', $node)->item(0)->nodeValue ?? null,
                'Nric'        => $xpath->query('./*[local-name()="Nric"]', $node)->item(0)->nodeValue ?? null,
            ];
        }

        return collect($data);
    }


}
