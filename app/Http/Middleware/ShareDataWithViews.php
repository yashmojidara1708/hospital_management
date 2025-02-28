<?php

namespace App\Http\Middleware;

use App\Models\PortfolioIndustries;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\GenerelSetting;
use App\Models\GenerelSetting_email;
use App\Models\GenerelSetting_number;
use App\Models\PortfolioCategory;
use App\Models\Services;
use App\Models\Socialmedia;
use App\Models\Banner_category;
use App\Models\GeneralSetting_footer_color;

class ShareDataWithViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = Services::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(4)->pluck('name')->toArray();
        $service_data = Services::where('status', "!=", "1")->get()->toArray();
     
        $services = Services::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(2);

        $socialMediaLinks = Socialmedia::all()->take(4)->map(function ($socialMedia) {
            return [
                'socialmedia' => $socialMedia->socialmedia,
                'links' => $socialMedia->links,
            ];
        });

        // $services = Services::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(2);

        // $about = About::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(3);

        $general_setting = GenerelSetting::all();
        $general_setting_logo = GenerelSetting::all();
        $general_setting_address = GenerelSetting::all();
        $general_setting_email = GenerelSetting_email::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(2);
        $general_setting_number = GenerelSetting_number::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(2);

        $general_setting_email_nav = GenerelSetting_email::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(1);
        $general_setting_number_nav = GenerelSetting_number::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(1);

        $portfolioCategory = PortfolioCategory::select('id', 'name')->where('status', '!=', 0)->get()->toArray();
        $industryCategory = PortfolioIndustries::select('id', 'name')->where('status', '!=', 0)->get()->toArray();

        $banner_categories = Banner_category::where('status', '1')->get();

        // banner home
        // $banner_home = Banner::join('banner_category', 'banners.banner_for', '=', 'banner_category.id')
        //     ->select('banners.*', 'banner_category.page as page_name')
        //     ->where('banner_category.page', 'home')
        //     ->whereNotIn('banners.status', [1, -1])
        //     ->take(3)
        //     ->get()
        //     ->toArray();

        // Details
        // $banner_details = Banner::join('banner_category', 'banners.banner_for', '=', 'banner_category.id')
        //     ->select('banners.*', 'banner_category.page as page_name')
        //     ->where('banner_category.page', 'Details')
        //     ->whereNotIn('banners.status', [1, -1])
        //     ->get()
        //     ->toArray();

        // portfollio
        // $banner_Portfollio = Banner::join('banner_category', 'banners.banner_for', '=', 'banner_category.id')
        //     ->select('banners.*', 'banner_category.page as page_name')
        //     ->where('banner_category.page', 'Portfollio')
        //     ->whereNotIn('banners.status', [1, -1])
        //     ->get()
        //     ->toArray();

        // about
        // $banner_about = Banner::join('banner_category', 'banners.banner_for', '=', 'banner_category.id')
        // ->select('banners.*', 'banner_category.page as page_name')
        // ->where('banner_category.page', 'About')
        // ->whereNotIn('banners.status', [1, -1])
        // ->get()
        // ->toArray();

        // Team
        // $banner_team = Banner::join('banner_category', 'banners.banner_for', '=', 'banner_category.id')
        // ->select('banners.*', 'banner_category.page as page_name')
        // ->where('banner_category.page', 'Team')
        // ->whereNotIn('banners.status', [1, -1])
        // ->get()
        // ->toArray();

        // service
        // $banner_service = Banner::join('banner_category', 'banners.banner_for', '=', 'banner_category.id')
        // ->select('banners.*', 'banner_category.page as page_name')
        // ->where('banner_category.page', 'Service')
        // ->whereNotIn('banners.status', [1, -1])
        // ->get()
        // ->toArray();

        $GeneralSetting_footer_color = GeneralSetting_footer_color::all()->toArray();

        // $testimonals = Testimonial::all()->take(4)->toArray();

        // $services_page = Services::all()->where('status', "!=", "1")->where('status', "!=", "-1")->take(8);

        $footercolor = GeneralSetting_footer_color::all();

        View::share([
            'GeneralSetting_footer_color' => $GeneralSetting_footer_color,
            'general_setting_email_nav' => $general_setting_email_nav,
            'general_setting_number_nav' => $general_setting_number_nav,
            // 'banner_service' => $banner_service,
            // 'banner_team' => $banner_team,
            // 'banner_about' => $banner_about,
            // 'banner_Portfollio' => $banner_Portfollio,
            // 'banner_details' => $banner_details,
            // 'banner_home' => $banner_home,
            'banner_categories' => $banner_categories,
            'portfolioCategory' => $portfolioCategory,
            'industryCategory' => $industryCategory,
            'general_setting_logo' => $general_setting_logo,
            'general_settings' => $general_setting,
            'general_setting_numbers' => $general_setting_number,
            'general_setting_emails' => $general_setting_email,
            'general_setting_address' => $general_setting_address,
            // 'abouts' => $about,
            // 'services' => $services,
            'names' => $data,
            'service_data' => $service_data,
            'socialMediaLinks' => $socialMediaLinks,
            'footercolor' => $footercolor,
            'services' => $services,
            // 'testimonals' => $testimonals,
            // 'services_page' => $services_page
        ]);

        return $next($request);
    }
}
