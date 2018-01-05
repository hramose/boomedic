<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;



class history extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
          $user = User::find(Auth::id());

        $dateUser = DB::table('users')->where('id', Auth::id())
           ->where( 'updated_at', '>', Carbon::now()->subDays(7))
            ->select('id','created_at','updated_at')->get();

        $dateSupport = DB::table('support_tickets')->where('userId', Auth::id())
           ->where( 'created_at', '>', Carbon::now()->subDays(7))
           ->select('id','created_at','updated_at','ticketDescription')->get();

        $datePayment = DB::table('paymentsmethods')->where('owner', Auth::id())
           ->where( 'created_at', '>', Carbon::now()->subDays(7))
           ->select('id','created_at','updated_at','provider','cardnumber')->get();

           $array = collect();
           
           foreach($dateSupport as $date){
            $car = new Carbon($date->created_at);
                $array[$date->updated_at]  = collect([
                            'Type'       => 'Support Ticket',
                            'id'         =>  $date->id,
                            'created_at' => $date->created_at,
                            'updated_at' => $date->updated_at,
                            'time'       => $car->diffForHumans(),
                            'des'        => $date->ticketDescription
                            ]);
           }
        foreach($dateUser as $date){
            $car = new Carbon($date->updated_at);
                $array[]  = collect([
                            'Type'       => 'User',
                            'id'         =>  $date->id,
                            'created_at' => $date->created_at,
                            'updated_at' => $date->updated_at,
                            'time'       => $car->diffForHumans(),
                            ]);
           }

           foreach($datePayment as $date){
           /* $dateTransaction = DB::table('transaction_bank')->where('payment', $date)
           ->where( 'created_at', '>', Carbon::now()->subDays(7))
           ->select('id','created_at','updated_at')->get(); */
           $car = new Carbon($date->created_at);

                $array[]  = collect([
                            'Type'       => 'Payment Method',
                            'id'         =>  $date->id,
                            'created_at' => $date->created_at,
                            'updated_at' => $date->updated_at,
                            'time'       => $car->diffForHumans(),
                            'typemethod' => $date->provider,
                            'cardnumber' => $date->cardnumber
                            ]);


           }



           foreach($array->sortByDesc('updated_at') as $items){
            if(Carbon::parse($items['created_at'])->format('d-m-Y') == Carbon::now()->format('d-m-Y') || Carbon::parse($items['updated_at'])->format('d-m-Y') == Carbon::now()->format('d-m-Y')){
                $arraynow[] = $items;
            }
            if(Carbon::parse($items['created_at'])->format('d-m-Y') == Carbon::now()->subDays(1)->format('d-m-Y') || Carbon::parse($items['updated_at'])->format('d-m-Y') == Carbon::now()->subDays(1)->format('d-m-Y')){
                $array1[] = $items;
            }
            if(Carbon::parse($items['created_at'])->format('d-m-Y') == Carbon::now()->subDays(2)->format('d-m-Y') || Carbon::parse($items['updated_at'])->format('d-m-Y') == Carbon::now()->subDays(2)->format('d-m-Y')){
                $array2[] = $items;
            }
            if(Carbon::parse($items['created_at'])->format('d-m-Y') == Carbon::now()->subDays(3)->format('d-m-Y') || Carbon::parse($items['updated_at'])->format('d-m-Y') == Carbon::now()->subDays(3)->format('d-m-Y')){
                $array3[] = $items;
            }
           }
       
            //dd($array);
           

        return view('history', [
                'userId'    => Auth::id(),
                'username'  => $user->username,
                'name'      => $user->name,
                'photo'     => $user->profile_photo,
                'dateUser'  => $dateUser,
                'array2'     => $array2,
                'array1'    => $array1,
                'array'     => $array,
                'arraynow'  => $arraynow

            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function redirecting($page)
    {
        switch ($page) {
            case 'index':
                return redirect('history/index'); //show
                break;
            
            default:
                return redirect('/medicalconsultations'); //medicalconsultations
                break;
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
