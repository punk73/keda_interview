<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    //
    public function index(Request $request) {
        if (!Gate::allows('see-customer')) {
            return [
                'success' => false,
                'message' => 'Access denied! only staff can see customer',
                'data' => Auth::user()
            ];
        }
        
        $customer = (new User())->customers()->get();
        return [
            'success' => true,
            'message' => 'data customer fetched!',
            'data' => $customer
        ];
    }

    public function delete($id, Request $request){
        
        if(!Gate::allows('delete-customer')) {
            return [
                'success' => false,
                'message' => 'Access denied! only staff can delete customer'
            ];    
        }
        
        $customer = User::findOrFail($id);
        if($customer->isCustomer()) {
            $customer->delete();
            return [
                'success' =>true,
                'message' => 'customer deleted!'
            ];
        }

        return [
            'success' => false,
            'message' => 'You cannot delete staff!'
        ];

    }
}
