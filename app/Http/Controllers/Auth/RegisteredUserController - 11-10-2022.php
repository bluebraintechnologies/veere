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
    public function store(Request $request)
    {

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

        Auth::login($user); 
        //add point of registration to user account
        $table = "transaction_".$user->id."c";
        $table_query = "CREATE TABLE IF NOT EXISTS `".$table."` (`id` int(10) NOT NULL AUTO_INCREMENT, `serial_no` varchar(200) DEFAULT NULL, `order_id` int(10) DEFAULT NULL, `description` text, `amt` int(10) DEFAULT NULL, `expiry_date` date DEFAULT NULL, `expiry` bigint(15) DEFAULT NULL, `date` date DEFAULT NULL, `trn_type` int(1) NOT NULL COMMENT 'cr:1,dr:2', `remaining` int(10) DEFAULT NULL, `status` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        DB::statement($table_query);
        $business = Business::first();
        if($business->enable_rp){
            if($request->referer_code){
                //find agent
                $reward_points = null;
                $agent = Agent_reg::where('referer_code', 'like', $request->referer_code)->first();
                if($agent){
                    if($agent->agent_type_id){
                        $agent_type = AgentType::find($agent->agent_type_id);
                        $agent_commission = AgentCommission::where('agent_type_id', $agent->agent_type_id)->orderBy('agent_type_id', 'desc')->first();
                        $reward_points = $agent_commission->reg_commission;
                    }
                }
                
            }
            //reward point module is active
            if(is_null($reward_points)){
                $reward_points = $business->rc_registration;
            }
            $expiry = $business->rc_expiry;
            $rp_name = $business->rp_name;
            if($business->cus_rc_name){
                $rp_name = $business->cus_rc_name;
            }
            $data = [
                'order_id' => null,
                'description' => 'On registeration, you got '.$reward_points.' '.$rp_name,
                'amt' => $reward_points,
                'remaining' => $reward_points,
                'expiry_date' => date('Y-m-d', strtotime('now') + 3600*24*$business->rc_expiry),
                'expiry' => strtotime('now') + 3600*24*$business->rc_expiry,
                'date' => date('Y-m-d', strtotime('now')),
                'trn_type' => 1,
                'status' => 1
            ];
            DB::table($table)->insert($data);
        }
        //now create agent if referer_code is given
        
        return redirect(RouteServiceProvider::HOME);
    }
}
