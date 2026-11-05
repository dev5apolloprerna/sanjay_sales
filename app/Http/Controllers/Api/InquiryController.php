<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreInquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends ApiController
{
    /**
     * POST /api/inquiries – "Inquire Now" form (contact page or a product page).
     * Saved for the admin panel and e-mailed to config('site.inquiry_email').
     */
    public function store(StoreInquiryRequest $request)
    {
        // Honeypot: bots fill the hidden "website" field. Pretend it worked, save nothing.
        if ($request->filled('website')) {
            return $this->ok(null, 'Thank you! Your inquiry has been submitted. Our team will contact you soon.', 201);
        }

        $inquiry = Inquiry::create($request->safe()->except('website') + [
            'source' => $request->product_id ? 'product' : 'contact',
            'ip_address' => $request->ip(),
        ]);

        $this->notifyAdmin($inquiry);

        return $this->ok(
            new InquiryResource($inquiry),
            'Thank you! Your inquiry has been submitted. Our team will contact you soon.',
            201
        );
    }

    /** E-mail is best-effort: a mail failure must not lose the saved inquiry. */
    private function notifyAdmin(Inquiry $inquiry): void
    {
        $to = config('site.inquiry_email');
        if (! $to) {
            return;
        }
        $inquiry->loadMissing('product:id,name');

        $body = implode("\n", array_filter([
            'New inquiry from the website',
            '',
            'Name: '.$inquiry->name,
            'Phone: '.$inquiry->phone,
            $inquiry->email ? 'Email: '.$inquiry->email : null,
            $inquiry->subject ? 'Subject: '.$inquiry->subject : null,
            $inquiry->product ? 'Product: '.$inquiry->product->name : null,
            '',
            $inquiry->message,
        ], fn ($l) => $l !== null));

        try {
            Mail::raw($body, function ($m) use ($to, $inquiry) {
                $m->to($to)->subject('New Inquiry – '.$inquiry->name);
                if ($inquiry->email) {
                    $m->replyTo($inquiry->email, $inquiry->name);
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Inquiry e-mail failed: '.$e->getMessage(), ['inquiry_id' => $inquiry->id]);
        }
    }
}
