<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $path = explode('/',url()->previous()); //dd($path);
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'pre_path_segment' => $path[count($path)-1],
            'pre_path' => url()->previous()
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {        
        $mobile = $request->mobile;
        $otp = $request->otp;
     
        $user = User::where('mobile', $mobile)->first();
        if($user){
            $user = User::where('mobile', $mobile)->first();
            $table = "transaction_".$user->id."c";
            if (!Schema::hasTable($table)) {
                $table_query = "CREATE TABLE IF NOT EXISTS `".$table."` (`id` int(10) NOT NULL AUTO_INCREMENT, `serial_no` varchar(200) DEFAULT NULL, `order_id` int(10) DEFAULT NULL, `description` text, `amt` int(10) DEFAULT NULL, `expiry_date` date DEFAULT NULL, `expiry` bigint(15) DEFAULT NULL, `date` date DEFAULT NULL, `trn_type` int(1) NOT NULL COMMENT 'cr:1,dr:2', `remaining` int(10) DEFAULT NULL, `status` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
                DB::statement($table_query);
            }
        }
        // $request->authenticate();
        Auth::login($user);
        $request->session()->regenerate();        
        $carts = Cart::where('user_id', Auth::user()->id)->count();
        if ($carts > 0) {
            return redirect()->route('checkout');
        }
        
        if($request->pre_path_segment == 'login' || $request->pre_path_segment == '' ){
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        return redirect()->route('dashboard');
        
        return redirect()->to($request->pre_path);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
