<?php

namespace App\Http\Controllers;

use App\Models\Sitecontent;
use App\Models\Meta_info_model;
use App\Models\Categories_model;
use App\Models\Partners_model;
use App\Models\Testimonial_model;
use App\Models\Faq_categories_model;
use App\Models\Faq_model;
use Illuminate\Http\Request;

class ContentPages extends Controller
{
    public function website_settings(Request $request){
        $site_language = $request->header('site_lang');
        $output['site_settings']=(object)[];
        $output['site_settings']=$this->getSiteSettings('en');
        $output['site_settings']->en=$this->getSiteSettings('en');
        $output['site_settings']->es=$this->getSiteSettings('es');
        exit(json_encode($output));
    }


    public function get_page_data($key,$site_lang){
        $row=Sitecontent::where('ckey',$key)->where('site_lang',$site_lang)->first();
        if($row):
          return unserialize($row->code);
        else:
            return false;
        endif;

    }
    public function home_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('home',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                $this->data['categories']=Categories_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->get();
                $this->data['brands']=Partners_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->get();
                $this->data['testimonials']=Testimonial_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->get();
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function login_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('signin',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/login')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function contact_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('contact',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/contact')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function terms_conditions_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('terms_conditions',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/terms-conditions')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function privacy_policy_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('privacy_policy',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/privacy-policy')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function signup_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('signup',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/signup')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function forgot_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('forgot',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/forgot-password')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function reset_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('reset',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/reset-password')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);

            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function about_page(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('about',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/about')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                $this->data['testimonials']=Testimonial_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->get();
                http_response_code(200);
                echo json_encode($this->data);
            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function become_a_youtuber(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('become_a_youtuber',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/become-youtuber')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);
            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function help(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('help',$post['site_lang']);
            if ($page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/help')->get()->first();
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                $faq_cats=Faq_categories_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->where('parent','=',0)->get();
                $help_arr=array();
                foreach($faq_cats as $faq_cat):
                    if($faq_cat->sub_cats->count() > 0):
                        $faqs_arr=array(
                            'title'=>$faq_cat->name,
                            'slug'=>$faq_cat->slug,
                            'questions'=>$faq_cat->sub_cats
                        );
                        $help_arr[]=$faqs_arr;
                    endif;
                endforeach;
                $this->data['faqs']=$help_arr;
                http_response_code(200);
                echo json_encode($this->data);
            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function help_category(Request $request){
        $post = $request->all();
        if($post):
            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('help',$post['site_lang']);
            $sub_category_slug=$request->input('sub_category_slug', null);
            if(!empty($sub_category_slug) && $sub_category_slug!=null && $sub_category_slug!='undefined'):
                $faq_cat=Faq_categories_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->where('slug','=',$sub_category_slug)->get()->first();
            else:
               $faq_cat=Faq_categories_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->where('slug','=',$post['slug'])->get()->first();
            endif;
            
            if ($faq_cat && $page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/help')->get()->first();
                $meta_info=(object)[];
                $meta_info->page_name=$faq_cat->name;
                $meta_info->slug=$faq_cat->slug;
                $meta_info->meta_title=$faq_cat->name." - ".$this->data['meta_info']->meta_title;
                $meta_info->meta_description=$faq_cat->name." - ".$this->data['meta_info']->meta_description;
                $meta_info->meta_keywords=$faq_cat->name." - ".$this->data['meta_info']->meta_keywords;
                $meta_info->og_title=$faq_cat->name." - ".$this->data['meta_info']->og_title;
                $meta_info->og_descriotion=$faq_cat->name." - ".$this->data['meta_info']->og_description;
                $meta_info->og_image=$this->data['meta_info']->og_image;
                $meta_info->image=$this->data['meta_info']->image;
                $this->data['meta_info']=$meta_info;
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                if(!empty($sub_category_slug) && $sub_category_slug!=null  && $sub_category_slug!='undefined'):
                    $this->data['faqs']=Faq_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->where('sub_category','=',$faq_cat->id)->get();
                else:
                    $this->data['faqs']=Faq_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->where('category','=',$faq_cat->id)->get();
                endif;
                $this->data['category']=$faq_cat;
                $this->data['content'] = $page;
                http_response_code(200);
                echo json_encode($this->data);
            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }
    public function help_question_details(Request $request){
        $post = $request->all();
        if($post):
            $this->data['post']=$post;

            if($post['site_lang'] !== 'es' && $post['site_lang'] !== 'en'):
                http_response_code(404);
                exit;
            endif;
            $page=$this->get_page_data('help',$post['site_lang']);
            $faq_row=Faq_model::where('site_lang','=',$post['site_lang'])->where('status','=',1)->where('slug','=',$post['question_slug'])->get()->first();
            if ($faq_row && $page):
                $this->data['meta_info']=Meta_info_model::where('site_lang','=',$post['site_lang'])->where('slug','=','/help')->get()->first();
                $meta_info=(object)[];
                $meta_info->page_name=$faq_row->question;
                $meta_info->slug=$faq_row->slug;
                $meta_info->meta_title=$faq_row->question." - ".$this->data['meta_info']->meta_title;
                $meta_info->meta_description=$faq_row->question." - ".$this->data['meta_info']->meta_description;
                $meta_info->meta_keywords=$faq_row->question." - ".$this->data['meta_info']->meta_keywords;
                $meta_info->og_title=$faq_row->question." - ".$this->data['meta_info']->og_title;
                $meta_info->og_descriotion=$faq_row->question." - ".$this->data['meta_info']->og_description;
                $meta_info->og_image=$this->data['meta_info']->og_image;
                $meta_info->image=$this->data['meta_info']->image;
                $this->data['meta_info']=$meta_info;
                $this->data['page_title'] = $this->data['meta_info']->page_name.' - '.$this->data['site_settings']->site_name;
                $this->data['content'] = $page;
                $this->data['faq_row'] = $faq_row;
                http_response_code(200);
                echo json_encode($this->data);
            else:
                http_response_code(404);
            endif;
            exit;
        else:
            http_response_code(404);
        endif;
    }

}
