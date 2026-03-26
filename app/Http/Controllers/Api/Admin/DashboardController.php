<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

use App\Models\Website\Order;
use App\Models\Website\Payment;
use App\Models\User;
use App\Models\Website\Contact; // contact form model

class DashboardController extends BaseController
{

    public function dashboardCounts()
    {

        $data = [

            'total_orders' => Order::count(),

            'total_collection' => Payment::where('status','paid')->sum('amount'),

            'total_pending_orders' => Order::where('order_status','pending')->count(),

            'total_payments' => Payment::count(),

            'total_customer' => User::where('role','user')->count(),

            'total_contacts' => Contact::count()

        ];

        return $this->success($data,'Dashboard data fetched successfully');

    }

    
    public function salesDashboardCounts()
    {

        $data = [

            'new_contacts' => Contact::where('status', 'new')->count(),
            'replied_contacts' => Contact::where('status', 'replied')->count(),
            'closed_contacts' => Contact::where('status', 'closed')->count(),

        ];

        return $this->success($data,'Dashboard data fetched successfully');

    }

    public function accountDashboardCounts()
    {
         $data = [

            'total_orders' => Order::count(),

            'total_collection' => Payment::where('status','paid')->sum('amount'),

            'total_pending_orders' => Order::where('order_status','pending')->count(),

            'total_payments' => Payment::count(),

        ];

        return $this->success($data,'Dashboard data fetched successfully');
    }

}