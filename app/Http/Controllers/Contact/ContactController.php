<?php

namespace App\Http\Controllers\Contact;

use App\Models\Contact\Contact;
use App\Http\Controllers\Controller;
use App\Traits\sendMails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    use sendMails;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::orderBy('created', 'desc')->paginate(50);
        return view('site.dashboard.contact.index', ['contacts' => $contacts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'name'    => ['required', 'string', 'max:190'],
            'email'   => ['required', 'email', 'max:190'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string'],
        ]);
        DB::beginTransaction();
        try {
            $contact = Contact::create(
                [
                    'name'    => $request->name,
                    'email'   => $request->email,
                    'subject' => $request->subject,
                    'message' => $request->message
                ]
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        alert()->success("Your message has been received. <br> You'll get a response from one of our Admins soonest");
        return redirect()->route('contact');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('site.dashboard.contact.show', ['contact' => $contact]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function get(Contact $contact)
    {
        return response()->json($contact->toArray());
    }

    /**
     * Send a newly created email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'to'    => ['required', 'string'],
            'bcc'        => ['string'],
            'cc'         => ['string'],
            'subject'    => ['required', 'string'],
            'message'    => ['required', 'string'],
            'attachment' => ['file'],
        ]);
        try {
            $details = array();
            $to = $request->to;
            $bcc = $request->bcc ?? null;
            $cc = $request->cc ?? null;
            $details['subject'] = $request->subject;
            $details['message'] = $request->message;
            if ($request->hasFile('attachment')) {
                $details['attachment'] = $request->file('attachment');
            }
            if ($request->has('personal') && ($request->personal == 'personal')) {
                $details['from'] = Auth::user()->email;
            }
            $this->sendMail($to, $bcc, $cc, $details);;
        } catch (\Throwable $th) {
            throw $th;
        }
        alert()->success('Email sent successfully!');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, Contact $contact)
    {
        $request->flash();
        $this->validate($request, [
            'bcc'        => ['string'],
            'cc'         => ['string'],
            'subject'    => ['required', 'string'],
            'message'    => ['required', 'string'],
            'attachment' => ['file'],
        ]);
        try {
            $details = array();
            $to = $contact->email;
            $bcc = $request->bcc ?? null;
            $cc = $request->cc ?? null;
            $details['subject'] = $request->subject;
            $details['message'] = $request->message;
            if ($request->hasFile('attachment')) {
                $details['attachment'] = $request->file('attachment');
            }
            if ($request->has('personal') && ($request->personal == 'personal')) {
                $details['from'] = Auth::user()->email;
            }
            $this->sendMail($to, $bcc, $cc, $details);;
        } catch (\Throwable $th) {
            throw $th;
        }
        alert()->success('Emaill sent successfully!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
