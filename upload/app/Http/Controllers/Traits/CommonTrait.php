<?php
namespace App\Http\Controllers\Traits;

use App\Mail\SendMail;
use App\Receiving;
use App\Sale;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;

trait CommonTrait
{
    public function sendMail($to, $template, $data) {
        Mail::to($to)->send(new SendMail($template, $data));
    }

    public function getMac()
    {
        $os_type = PHP_OS;
        $mac_addr = "";
        
        switch ( strtolower($os_type) ){
            case "linux":
                $mac_addr = $this->getMacLinux();
                break;
            case "solaris":
                break;
            case "unix":
                break;
            case "aix":
                break;
            default:
                $mac_addr = $this->getMacWindows();
                break;
        }
            
        return trim($mac_addr);
    }

    public function getMacWindows()
    {
        $mac = "";
        exec("ipconfig /all", $output);
        foreach($output as $line){
            if (preg_match("/(.*)Physical Address(.*)/", $line)){
            $mac = $line;
            $mac = str_replace("Physical Address. . . . . . . . . :","",$mac);
            }
        }
        return trim($mac);
    }

    public function getMacLinux()
    {
        $mac="";
        $result = array();
        exec('netstat -ie', $result);
        if(is_array($result) && !empty($result)) {
         $iface = array();
         foreach($result as $key => $line) {
           if($key > 0) {
             $tmp = str_replace(" ", "", substr($line, 0, 10));
             if($tmp <> "") {
               $macpos = strpos($line, "HWaddr");
               if($macpos !== false) {
                 $iface[] = array('iface' => $tmp, 'mac' => strtolower(substr($line, $macpos+7, 17)));
               }
             }
           }
         }
         if (!empty($iface)) {
            $mac = $iface[0]['mac'];
         }
       } 
       return $mac;
    }

    public function getClientIp()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function checkIns($user_name, $purchase_code, $product_id)
    {
        $client = new Client();
        $url = config('installer.auri').config('app.owner_url').'/api/veriation';
        $res = $client->request('POST', $url, [
            'form_params' => [
                '_token' => csrf_token(),
                "user_name"=>$user_name,
                "purchase_code"=> $purchase_code,
                "mac"=>$this->getMac(),
                "product_id"=> $product_id
            ]
        ]);
        return $res;
    }

    public function getInvoiceNo($option=null) {
        if ($option['name'] == 'SALE') {
            if (empty($option['id'])) {
                $last_sale = Sale::orderBy('id', 'desc')->first();
                if (empty($last_sale)) {
                    $option['id'] = 1;
                } else {
                    $option['id'] = $last_sale->id + 1;
                }
            }
            $invoice_no = Sale::INVOICE_PREFIX.sprintf("%04d", $option['id']);
        } else if ($option['name'] == 'REC') {
            if (empty($option['id'])) {
                $last_rec = Receiving::orderBy('id', 'desc')->first();
                if (empty($last_rec)) {
                    $option['id'] = 1;
                } else {
                    $option['id'] = $last_rec->id + 1;
                }
            }
            $invoice_no = Receiving::INVOICE_PREFIX.sprintf("%04d", $option['id']);
        }
        return $invoice_no;
    }

    public function processNotification($notify) {
        $response = [];
        if(!empty($notify)) {
            if(is_array($notify)) {
                foreach($notify as $key=>$value) {
                    $response['notify'] = [$key=>$value];
                }
            } else {
                $response['notify'] = ['success'=>$notify];
            }
        }
        return $response;
    }
}