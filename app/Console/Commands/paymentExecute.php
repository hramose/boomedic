<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\PaymentMethod;
use App\email;
use Mail;
use App\Http\Controllers\payments;

class paymentExecute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentExecute:send';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to payment no procesed';
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
        $pending = DB::table('transaction_bank')
         ->join('paymentsmethods', 'transaction_bank.paymentmethod', '=', 'paymentsmethods.id')
         ->where('transaction_bank.status', 'Pending')
         ->select('transaction_bank.*','paymentsmethods.id as pay')
         ->get();

          if(count($pending) > 0){ 
            foreach($pending as $pen){
            $idpay = $pen->pay;
            $idtrans = $pen->id;

            $this->payments = new payments;
            $this->payments->PaymentAuthorizations($idpay, $idtrans);

                }
             }
        
    }
}