<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\UserCampaignPoint;
use App\Models\UsersPrePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignGuideController extends Controller
{
    public function faq($campaign_id)
    {
        $user = Auth::guard("user")->user();
        $campaign = Campaign::findOrFail($campaign_id);
        if($user) {
            $point = UserCampaignPoint::getLoggedInUserPoint($campaign_id);
            return view("user.faq", [
                'category_name' => 'guide',
                'page_name' => 'guide_faq',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
                'title' => 'よくある質問',
                "campaign" => $campaign,
                "point" => $point,
                "user" => $user,
            ]);
        } else {
            return view("user.faq", [
                'category_name' => 'guide',
                'page_name' => 'guide_faq',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
                'title' => 'よくある質問',
                "campaign" => $campaign,
            ]);
        }
    }
    public function guide($campaign_id)
    {
        $user = Auth::guard("user")->user();
        $campaign = Campaign::findOrFail($campaign_id);
        if($user) {
            $point = UserCampaignPoint::getLoggedInUserPoint($campaign_id);
            return view("user.guide", [
                'category_name' => 'guide',
                'page_name' => 'guide_guide',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
                'title' => '当サイトのご利用にあたって',
                "campaign" => $campaign,
                "point" => $point,
                "user" => $user,
            ]);
        } else {
            return view("user.guide", [
                'category_name' => 'guide',
                'page_name' => 'guide_guide',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
                'title' => '当サイトのご利用にあたって',
                "campaign" => $campaign,
            ]);
        }
    }
    public function privacypolicy($campaign_id)
    {
        $user = Auth::guard("user")->user();
        $campaign = Campaign::findOrFail($campaign_id);
        if($user) {
            $point = UserCampaignPoint::getLoggedInUserPoint($campaign_id);
            return view("user.privacypolicy", [
                'category_name' => 'guide',
                'page_name' => 'guide_privacypolicy',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
                'title' => 'プライバシーポリシー',
                "campaign" => $campaign,
                "point" => $point,
                "user" => $user,
            ]);
        } else {
            return view("user.privacypolicy", [
                'category_name' => 'guide',
                'page_name' => 'guide_privacypolicy',
                'has_scrollspy' => 0,
                'scrollspy_offset' => '',
                'title' => 'プライバシーポリシー',
                "campaign" => $campaign,
            ]);
        }
    }
}
