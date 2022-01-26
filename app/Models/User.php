<?php

namespace App\Models;

use App\Notifications\User\ResetPassword;
use App\Notifications\User\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * @return int 加算された合計プレポイント。
     */
    public function combinePrePoint($campaign_id): int
    {
        $prepoints = UsersPrePoint::join("receipts", "users_pre_points.receipt_id", "=", "receipts.id")
            ->where("receipts.user_id", $this->id)
            ->where("receipts.campaign_id", $campaign_id)
            ->where("users_pre_points.updated_at", "<", Carbon::now()->subHours(120)->toDateTime())
            ->get("users_pre_points.*")
            ;

        if($prepoints->isEmpty()) return 0;

        $total_prepoint = $prepoints->sum("point");

        DB::transaction(function() use($campaign_id, $prepoints, $total_prepoint) {
            UserCampaignPoint::where("user_id", $this->id)
                ->where("campaign_id", $campaign_id)
                ->update([
                    "remaining_point" => DB::raw("remaining_point + $total_prepoint"),
                    "total_point" => DB::raw("total_point + $total_prepoint"),
                ]);
            UsersPrePoint::whereIn("id", $prepoints->pluck("id"))->delete();
        });

        return $total_prepoint;
    }

    public function isJoinedCampaign($campaign_id)
    {
        return UserCampaignPoint::loggedInCampaign($campaign_id)->first() ? true : false;
    }

    public function joinCampaign($campaign_id)
    {
        $point = new UserCampaignPoint();
        $point->user_id = Auth::guard("user")->id();
        $point->campaign_id = $campaign_id;
        $point->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        "provider", "provider_user_id",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
