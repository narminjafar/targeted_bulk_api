<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $content;
    public User $user;
    public int $campaignId;

    public function __construct(string $subjectLine, string $content, User $user, int $campaignId)
    {
        $this->subject($subjectLine);
        $this->content = $content;
        $this->user = $user;
        $this->campaignId = $campaignId;
    }

    public function build()
    {
        $unsubscribeUrl = URL::signedRoute('unsubscribe', [
            'user' => $this->user->id,
            'campaign' => $this->campaignId,
        ]);

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.campaign')
            ->with([
                'content' => $this->content,
                'unsubscribeUrl' => $unsubscribeUrl,
                'user' => $this->user,
            ]);
    }
}
