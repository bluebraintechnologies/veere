<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent_reg;
use App\Models\AgentType;
use App\Models\AgentCommission;
use App\Models\Business;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Support\Facades\Schema;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendOtp($mobile){
        return rand(1000, 9999);
    }
    public function store(Request $request)
    {
        if($request->password){

        }else{
            //check && save
            // dd($request->all());
            $mobile = $request->mobile;
            if($user = User::where('mobile', 'like', $mobile)->exists()){
                //create and send otp
                $this->sendOtp($mobile);
            }else{
                $user = User::create(['mobile' => $mobile ]);                
                $user->otp = $this->sendOtp($mobile);
                $user->save();
                $request->validate([
                    'otp' => 'required'
                ], []);
            }
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:contacts',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $name = explode(' ', $request->name);
        
        $user = User::create([
            'first_name' => $name[0],
            'last_name' => (count($name)>=2)?$name[1]:'',
            'name' => $request->name,
            'type' => 'customer',
            'business_id' => 1,
            'referer_code' => $request->referer_code,
            'email' => $request->email,
            'contact_status' => 'active',
            'created_by' => 4,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Auth::login($user);
        //add point of registration to user account
        $table = "transaction_".$user->id."c";
        $table_query = "CREATE TABLE IF NOT EXISTS `".$table."` (`id` int(10) NOT NULL AUTO_INCREMENT, `serial_no` varchar(200) DEFAULT NULL, `order_id` int(10) DEFAULT NULL, `description` text, `amt` int(10) DEFAULT NULL, `expiry_date` date DEFAULT NULL, `expiry` bigint(15) DEFAULT NULL, `date` date DEFAULT NULL, `trn_type` int(1) NOT NULL COMMENT 'cr:1,dr:2', `remaining` int(10) DEFAULT NULL, `status` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        DB::statement($table_query);
        $business = Business::first();
        if($business->enable_rp){
            if($request->referer_code){
                //find agent
                $agent = Agent_reg::where('referer_code', 'like', $request->referer_code)->first();
                $table_name = "transaction_".$agent->id."a";
                if (Schema::hasTable($table_name)) {
                    $rp_name = $business->rp_name;
                    if($business->cus_rc_name){
                        $rp_name = $business->cus_rc_name;
                    }
                    $reward_points = $business->rc_registration;
                    $data = [
                        'user_id' => $user->id,
                        'description' => 'On registeration, you got '.$reward_points.' '.$rp_name,
                        'amt' => $reward_points,
                        'remaining' => $reward_points,
                        'expiry_date' => date('Y-m-d', strtotime('now') + 3600*24*$business->rc_expiry),
                        'expiry' => strtotime('now') + 3600*24*$business->rc_expiry,
                        'date' => date('Y-m-d', strtotime('now')),
                        'trn_type' => 1,
                        'status' => 1
                    ];
                    DB::table($table_name)->insert($data);
                }
            }
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
