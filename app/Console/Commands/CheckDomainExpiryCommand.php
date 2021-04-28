<?php

namespace App\Console\Commands;

use App\Monitor;
use GuzzleHttp\Client;

use Illuminate\Console\Command;
class CheckDomainExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:check-domain-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $monitors=Monitor::orderBy('id','asc')->get();
        $url = 'http://6a084f3a57de.ngrok.io/domain-expiry';
        $method = 'GET';
       
        foreach($monitors as $monitor)
        {
            $domain = $monitor->url;
            $content=$this->guzzuleRequest($url, $method, $domain);
            if($content['success'] == true)
            {
                dd($content);
            }
        }
    }
    
    private function guzzuleRequest($url, $method, $domain)
    {
        

        $client = new Client(['verify' => false]);
        $headers = [
            // 'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
       
        $response = $client->request($method,$url,[
            'headers' => $headers,
            'json' => [
                    "domain" => $domain
                ]
        ]);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $content = json_decode($response->getBody(), true);
        // dd( $body);
        return $content;
    }
}
