<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Address; 
use Auth;

class AddressController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            // 'id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);
        $data = [
            "user_id" => Auth::user()->id,
            "name" => $request->name,
            "phone" => $request->phone,
            "landmark" => $request->landmark,
            "address" => $request->address,
            "city" => $request->city,
            "postal_code" => $request->postal_code,
        ];
        Address::create($data);
        return ['status' => 'success'];
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);        
        $data = [
            "name" => $request->name,
            "phone" => $request->phone,
            "address" => $request->address,
            "landmark" => $request->landmark,
            "city" => $request->city,
            "postal_code" => $request->postal_code,
        ];
        Address::where('id', $request->id)->update($data);
        return ['status' => 'success'];
    }
    public function defaultDeliveryAddress(Request $request){
        $address = $request->address;
        Address::where('user_id', Auth::user()->id)->update(['set_default' => null]);
        Address::where('user_id', Auth::user()->id)->where('id', $address['id'])->update(['set_default' => 1]);        
        return ['status' => 'success'];
    }
    public function defaultBillingAddress(Request $request){
        $address = $request->address;
        Address::where('user_id', Auth::user()->id)->update(['set_default_billing' => null]);
        Address::where('user_id', Auth::user()->id)->where('id', $address['id'])->update(['set_default_billing' => 1]);        
        return ['status' => 'success'];
    }
    public function setDefaultShippingBillingAddress(Request $request){
        $shipAddress = $request->shipAddress;
        Address::where('user_id', Auth::user()->id)->update(['set_default' => null]);
        Address::where('user_id', Auth::user()->id)->where('id', $shipAddress)->update(['set_default' => 1]);   
        $billingAddress = $request->billingAddress;
        Address::where('user_id', Auth::user()->id)->update(['set_default_billing' => null]);
        Address::where('user_id', Auth::user()->id)->where('id', $billingAddress)->update(['set_default_billing' => 1]);   
        return ['status' => 'success'];
    }
    public function setDefaultShippingAddress(Request $request){
        $shipAddress = $request->shipAddress;
        Address::where('user_id', Auth::user()->id)->update(['set_default' => null]);
        Address::where('user_id', Auth::user()->id)->where('id', $shipAddress)->update(['set_default' => 1]);
        return ['status' => 'success'];
    }
    public function setDefaultBillingAddress(Request $request){
        $billingAddress = $request->billingAddress;
        Address::where('user_id', Auth::user()->id)->update(['set_default_billing' => null]);
        Address::where('user_id', Auth::user()->id)->where('id', $billingAddress)->update(['set_default_billing' => 1]);   
        return ['status' => 'success'];
    }
    public function unsetDefaultShippingBillingAddress (){
        Address::where('user_id', Auth::user()->id)->update(['set_default' => null]);
        Address::where('user_id', Auth::user()->id)->update(['set_default_billing' => null]);
        
        return ['status' => 'success'];
    }
}
