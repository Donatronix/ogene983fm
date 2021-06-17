<?php

namespace App\Http\Controllers\Newsletter;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSendFormRequest;
use App\Http\Requests\SubscriptionFormRequest;
use App\Models\Newsletter\Newsletter;
use App\Models\Newsletter\NewsletterSubscription;
use App\Traits\ControllerTrait;
use App\Traits\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NewsletterController extends Controller
{
    use ControllerTrait;
    use SendEmail;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = Newsletter::orderBy('created_at', 'desc')->get();
        return view('site.dashboard..newsletter.index', ['newsletters' => $newsletters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.dashboard..newsletter.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia(Request $request, Newsletter $newsletter)
    {
        DB::beginTransaction();
        try {
            $mediaItem = Media::findOrFail($request->slug);
            $mediaItem->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 200);
        }
        DB::commit();
        return response()->json(['result' => view('site.dashboard..newsletter.file-list', ['files' => $newsletter->getMedia('newsletter')])->render()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function uploadMedia(Request $request)
    {
        DB::beginTransaction();
        try {
            $newsletter = Newsletter::whereSlug($request->newsletter)->first();
            if (empty($newsletter)) {
                $newsletter = new Newsletter;
                $newsletter->slug = $request->newsletter;
                $newsletter->subject = $request->newsletter;
                $newsletter->message = $request->newsletter;
                $newsletter->save();
            }
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                foreach ($request->file('file') as $file) {
                    $newsletter->uploadMedia($file, 'newsletter');
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 200);
        }
        DB::commit();
        return response()->json(['result' => view('site.dashboard..newsletter.file-list', ['files' => $newsletter->getMedia('newsletter')])->render()], 200);
    }

    /**
     * Process newsletter either for send or save
     *
     * @param \App\Http\Requests\NewsletterSendFormRequest $request
     * @param \App\Models\Newsletter\Newsletter $newsletter
     *
     * @return \Illuminate\Http\Response
     */
    public function process(NewsletterSendFormRequest $request)
    {

        $newsletter = Newsletter::whereSlug($request->newsletter)->first();
        if (isset($_POST['save'])) {
            return $this->saveDraft($request);
        } elseif (isset($_POST['sendUpdate'])) {
            return $this->update($request, $newsletter);
        } elseif (isset($_POST['update'])) {
            return $this->update($request, $newsletter, false);
        }
        //otherwise send
        return $this->send($request);
    }

    /**
     * Send a newly created email.
     *
     * @param  \App\Http\Requests\NewsletterSendFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDraft(NewsletterSendFormRequest $request)
    {
        $request->flash();
        $request->validated();
        try {
            $newsletter = Newsletter::create([
                'subject' => $request->subject,
                'message' => $request->message
            ]);
            if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                foreach ($request->file('attachment') as $file) {
                    $newsletter->uploadMedia($file, 'newsletter');
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        session()->flash('success', 'Newsletter saved as draft successfully!');
        return redirect()->route('newsletter.dashboard');
    }

    /**
     * Send draft newsletters.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendDraft(Newsletter $newsletter)
    {
        DB::beginTransaction();
        try {
            $emails = Newsletter::get();
            foreach ($emails as $to) {
                $details = [];
                $details['newsletter'] = 'newsletter';
                $details['subject'] = $newsletter->subject;
                $details['message'] = $newsletter->message;
                $details['route'] = route('newsletter.unsubscribe', ['newsletterSubscriber' => $to->slug]);
                foreach ($newsletter->getMedia('Newsletter') as $file) {
                    $details['attachment'][] = $file->getPath();
                }
                $body = view('emails.newsletter', ['details' => $details, 'title' => 'Newsletter'])->render();
                $this->send($to, $details['subject'], $body, $newsletter->getMedia('Newsletter'));
                $newsletter->update(['status' => 'sent']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', 'There was an error sending the newsletter!<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        session()->flash('success', "Newsletter sent successfully to " . $emails->count() . " email addresses!");
        return redirect()->route('newsletter.dashboard');
    }

    /**
     * Send a newly created email.
     *
     * @param  \App\Http\Requests\NewsletterSendFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function send(NewsletterSendFormRequest $request)
    {
        $request->validated();
        DB::beginTransaction();
        try {
            $emails = Newsletter::get();
            if ($emails) {
                foreach ($emails as $to) {
                    $details = [];
                    $details['newsletter'] = 'newsletter';
                    $details['subject'] = $request->subject;
                    $details['message'] = $request->message;
                    $details['route'] = route('newsletter.unsubscribe', ['newsletterSubscriber' => $to->slug]);
                    if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                        foreach ($request->file('attachment') as $file) {
                            $details['attachment'][] = $file->getRealPath();
                        }
                    }
                    $body = view('emails.newsletter', ['details' => $details, 'title' => 'Newsletter'])->render();
                    $this->send($to, $details['subject'], $body);
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', 'There was an error sending the newsletter!<br/>' . $th->getMessage());
            return back();
        }
        DB::commit();
        session()->flash('success', "Newsletter sent successfully to " . $emails->count() . " email addresses!");
        return redirect()->route('newsletter.dashboard');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubscriptionFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(SubscriptionFormRequest $request)
    {
        $request->flash();
        $exists = NewsletterSubscription::emailExists($request->email);
        if ($exists) {
            session()->flash('success', 'Newsletter', 'You are already subscribed!');
            return back();
        }
        // Will return only validated data
        $request->validated();
        DB::beginTransaction();
        try {
            NewsletterSubscription::create([
                'name'   => $request->name,
                'email' => $request->email,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', 'Newsletter', 'Thank you for subscribing to our newsletter');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(NewsletterSubscription $newsletter)
    {
        DB::beginTransaction();
        try {
            $subscriber = NewsletterSubscription::findOrFail($newsletter->id);
            $subscriber->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        return view('welcome', ['message' => 'You have been successfully unsubscribed from our newsletter.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function show(Newsletter $newsletter)
    {
        return response()->json([
            'subject' => $newsletter->subject,
            'message' => $newsletter->message,
            'editLink' => $newsletter->editLink,
            'sendLink' => $newsletter->sendLink,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function showDrafts()
    {
        $newsletters = Newsletter::whereStatus('draft')->get();
        return view('site.pages.Blog', ['blogs' => $newsletters]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function edit(Newsletter $newsletter)
    {
        return view('site.dashboard..newsletter.edit', ['newsletter' => $newsletter]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\NewsletterSendFormRequest  $request
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function update(NewsletterSendFormRequest $request, Newsletter $newsletter, bool $send = true)
    {
        $request->flash();
        // Will return only validated data
        $request->validated();
        DB::beginTransaction();
        try {

            $newsletter->update([
                'subject'   => $request->subject,
                'message' => $request->message,
            ]);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                foreach ($request->file('file') as $key => $file) {
                    $newsletter->uploadMedia($file, 'newsletter');
                }
            }
            // check if to send
            if ($send) {
                $this->send($request, $newsletter->slug);
                $newsletter->update(['status' => 'sent']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        if ($send) {
            session()->flash('success', 'Newsletter', 'The newsletter was updated and sent successfully!');
        } else {
            session()->flash('success', 'Newsletter', 'The newsletter was updated successfully!');
        }
        return redirect()->route('newsletter.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Newsletter\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Newsletter $newsletter)
    {
        DB::beginTransaction();
        try {
            $newsletter = Newsletter::findOrFail($newsletter->id);
            $newsletter->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
        session()->flash('success', 'Newsletter', 'The newsletter was deleted successfully!');
        return back();
    }
}
