<?php

namespace App\Listeners;

use App\Events\BidPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
// app/Listeners/SendBidNotification.php
use App\Services\FirebaseNotificationService;

class SendBidNotification
{
    public function __construct(
        protected FirebaseNotificationService $firebase
    ) {}

    public function handle(BidPlaced $event): void
    {
        $auction = $event->auction;
        $bid = $event->bid;

        // Notif ke pemilik lelang
        $owner = $auction->user;
        if ($owner->fcm_token) {
            $this->firebase->sendToDevice(
                token: $owner->fcm_token,
                title: 'Ada Bid Baru!',
                body: "Lelang {$auction->title} ditawar Rp " . number_format($bid->amount),
                data: ['auction_id' => (string) $auction->id]
            );
        }

        // Notif ke bidder lain yang kalah
        $auction->bids()
            ->with('user')
            ->where('user_id', '!=', $bid->user_id)
            ->get()
            ->each(function ($oldBid) use ($auction, $bid) {
                if ($oldBid->user->fcm_token) {
                    $this->firebase->sendToDevice(
                        token: $oldBid->user->fcm_token,
                        title: 'Kamu Tertawari!',
                        body: "Ada yang menawar lebih tinggi di lelang {$auction->title}",
                        data: ['auction_id' => (string) $auction->id]
                    );
                }
            });
    }

}
