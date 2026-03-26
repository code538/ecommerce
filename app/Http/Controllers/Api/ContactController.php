<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactController extends BaseController
{
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'    => 'required|string|max:255',
            //'company_name' => 'nullable|string|max:255',
            'email'        => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'subject'      => 'required|string|max:255',
            'message'      => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $contact = Contact::create([
            'full_name'    => $request->full_name,
            //'company_name' => $request->company_name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'subject'      => $request->subject,
            'message'      => $request->message,
            'status'       => 'new',
        ]);

        // $adminEmail = "ranjan10@yopmail.com";

        // Mail::send('email-template.admin-contact', ['contact' => $contact], function ($message) {
        //     $message->to("ranjan10@yopmail.com")
        //             ->subject("New Contact Form Submission")
        //             ->from("info@skilledworkerscloud.co.uk", "SWC");
        // });


        // // Send Confirmation Email to User
        // $firstName = explode(' ', $contact->full_name)[0];

        // $data = [
        //     'first_name'   => $firstName,
        //     'full_name'    => $contact->full_name,
        //     'email'        => $contact->email,
        //     'subject'      => $contact->subject,
        //     'reference_id' => 'SWC-' . str_pad($contact->id, 6, '0', STR_PAD_LEFT),
        //     'submitted_at' => now()->format('d M Y, h:i A'),
        //     'user_message' => $contact->message,
        // ];

        // Mail::send('email-template.contact-confirmation', $data, function ($message) use ($contact) {
        //     $message->to($contact->email, $contact->full_name)
        //             ->subject("Thank You for Contacting Skilled Workers Cloud")
        //             ->from("info@skilledworkerscloud.co.uk", "Skilled Workers Cloud");
        // });

        return $this->success($contact, 'Message sent successfully');
    }

    /**
     * Admin: List all messages
     */
    public function index()
    {
        $contacts = Contact::orderBy('id', 'desc')->get();

        return $this->success($contacts, 'Messages fetched successfully');
    }

    /**
     * Admin: Show single message
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (! $contact) {
            return $this->error('Message not found', [], 404);
        }

        return $this->success($contact, 'Message fetched successfully');
    }

    /**
     * Admin: Update message status
     */
    public function updateStatus(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (! $contact) {
            return $this->error('Message not found', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,replied,closed',
            'minutes'=> 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $contact->status = $request->status;
        $contact->minutes = $request->minutes;
        $contact->save();

        return $this->success($contact, 'Status updated successfully');
    }

    /**
     * Admin: Delete message
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (! $contact) {
            return $this->error('Message not found', [], 404);
        }

        $contact->delete();

        return $this->success([], 'Message deleted successfully');
    }
}
