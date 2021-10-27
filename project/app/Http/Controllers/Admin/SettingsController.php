<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Generalsetting as GS;
use App\Models\Language;
use App\Models\Localization as LC;
use App\Helpers\Autoload;
use App\Http\Traits\Localization;
use App\Models\Generalsetting;
use Carbon\Carbon;
use Validator;
use Session;

class SettingsController extends Controller
{
    use Localization;

    public function update(Request $request)
    {
        
        $gs = GS::find(1);

        $rules =[
            'email' => 'email'
        ];

        if($request->logo != null){
            $rules['logo'] = 'mimes:jpg,jpeg,png';
        }
        if($request->favicon != null){
            $rules['favicon'] = 'mimes:jpg,jpeg,png';
        }
        if($request->loader != null){
            $rules['loader'] = 'mimes:gif';
        }
        if($request->admin_loader != null){
            $rules['admin_loader'] = 'mimes:gif';
        }
        if($request->breadcamp != null){

            $rules['breadcamp'] = 'mimes:jpg,jpeg,png';
        }
        if($request->footer_logo != null){
            $rules['footer_logo'] = 'mimes:jpg,jpeg,png';
        }
        if($request->favicon != null){
            $rules['favicon'] = 'mimes:jpg,jpeg,png';
        }

        $data = $request->except(['_token','sociallink']);
        $data['sociallink'] = json_encode($request->sociallink);
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        if ($file = $request->file('logo')) {
            if(file_exists(base_path('/assets/images/'.$gs->logo))){
                unlink(base_path('/assets/images/'.$gs->logo));
            }
            $name = time().'-logo.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            $data['logo'] = $name;
        }
        if ($file = $request->file('footer_logo')) {
            if(file_exists(base_path('/assets/images/'.$gs->footer_logo))){
                unlink(base_path('/assets/images/'.$gs->footer_logo));
            }
            $name = time().'-footer-logo.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            $data['footer_logo'] = $name;
        }
        if ($file = $request->file('loader')) {
            if(file_exists(base_path('/assets/images/'.$gs->loader))){
                unlink(base_path('/assets/images/'.$gs->loader));
            }
            $name = time().'-loader.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            $data['loader'] = $name;
        }
        if ($file = $request->file('admin_loader')) {
            if(file_exists(base_path('/assets/images/'.$gs->admin_loader))){
                unlink(base_path('/assets/images/'.$gs->admin_loader));
            }
            $name = time().'-admin_loader.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            $data['admin_loader'] = $name;
        }
        if ($file = $request->file('breadcamp')) {
            if(file_exists(base_path('/assets/images/'.$gs->breadcamp))){
                unlink(base_path('/assets/images/'.$gs->breadcamp));
            }
            $name = time().'-breadcamp.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            $data['breadcamp'] = $name;
        }
        if ($file = $request->file('favicon')) {
            if(file_exists(base_path('assets/images/'.$gs->favicon))){
                unlink(base_path('/assets/images/'.$gs->favicon));
            }
            $name = time().'-favicon.'.$file->getClientOriginalExtension();
            $file->move('assets/images',$name);
            $data['favicon'] = $name;
        }


        GS::where('id',1)->update($data);
        
        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    public function index()
    {
       return view('admin.settings.theme'); 
    }


    public function localizationFormShow()
    {
        $data['languageData'] = LC::orderBy('id','desc')->get();
        $data['languageList'] = Language::orderBy('id','asc')->get();
        return view('admin.settings.localization',$data);  
    }

    public function languageStatus()
    {
        $language = LC::find(request()->id);
        if($language->is_default == 1){
            Session::flash('Warning','This language is default, You can\'t change it\'s staus!.');    
            return redirect()->back();
        }
        LC::where(['id'=>request()->id])->update(['status'=>request()->value]);
        if(request()->value == 0){
            Session::flash('Success','Deactive Language Successfully.');
        }else{
            Session::flash('Success','Active Language Successfully.');
        }
        return redirect()->back();
    }

    public function languageSetDefault()
    {
        $language = LC::find(request()->id);
        if($language->status == 0){
            Session::flash('Warning','This language is deactived, You can\'t change it\'s default!.');    
            return redirect()->back();
        }
        LC::where('is_default',1)->update(['is_default'=>0]);
        LC::where(['id'=>request()->id])->update(['is_default'=>1]);
        Session::flash('Success','Set Language Default Successfully.');
        return redirect()->back();
    }

    public function languageEditForm()
    {
        
        if(!request()->lid){
            return redirect()->route('error404');
        }

        $data = LC::findOrFail(request()->lid);
        $langList = file_get_contents(base_path().'/resources/lang/'.$data->file);
        $langkey= file_get_contents(base_path().'/resources/lang/en.json');
        $data['langList']   = json_decode($langList,true);
        $data['langkey']  = json_decode($langkey,true);
        $data['language'] = LC::find(request()->lid);
        return view('admin.settings.language-edit',$data);
    }

    public function languageAdd(Request $request)
    {

        $file =  $request->language.'.json';
        $data = array(
            'language_code' => $request->language,
            'file' => $file,
            'created_at' => Carbon::now()
        );
        $base_path = base_path('resources/lang/'.$file);
        $defaultData = file_get_contents(base_path('resources/lang/'.'default.json'));
 
        if(!file_exists($base_path)){
            fopen($base_path,'w');
            file_put_contents($base_path,$defaultData);
        }
        LC::insert($data);
        Session::flash('Success','Language inserted successfully!');
        return redirect()->back();
    }

    public function languageDelete($lang)
    {
        $data = LC::where('language_code',$lang)->first();
        if($data->language_code == 'en'){
            Session::flash('Warning','You can\'t delete, fafa This Language is default.');
            return redirect()->back(); 
        }


        if($data->is_default == 0){
            $base_path = base_path('resources/lang/'.$lang.'.json');
            if(file_exists($base_path)){
                unlink($base_path);
            }
            LC::where('language_code',$lang)->delete();
            Session::flash('Success','Language Delated Successfully!');
        }else{
            Session::flash('Warning','You can\'t delete, This Language is default.');
        }
        return redirect()->back();
    }


    public function languageUpdate(Request $request)
    {
        $data = array();
        
        $data_file = $request->language_code.'.json';
        file_put_contents(base_path().'/resources/lang/'.$data_file,'{}');
        $data_results = file_get_contents(base_path().'/resources/lang/'.$data_file);
        $langkey= file_get_contents(base_path().'/resources/lang/en.json');
        $langkey = json_decode($langkey,true);
        $array_keys = array_keys($langkey);

        $data_results = json_decode($data_results,true);

        foreach($request->language as $key => $value){
            if($request->language_code == 'en'){
                $data_results[$value] = trim($value);
            }else{
                $data_results[$array_keys[$key]] = $value;
            }
        }
        $data = json_encode($data_results);
        file_put_contents(base_path().'/resources/lang/'.$data_file,$data);

        return response()->json('Update Information Successfully!');
    }

    public function status($field,$value)
    {
        $prev = '';
        $data = Generalsetting::find(1);
        if($field == 'is_debug'){
            $prev = $data->is_debug == 1 ? 'true':'false';
        }
        $data[$field] = $value;
        $data->update();
        if($field == 'is_debug'){
            $now = $data->is_debug == 1 ? 'true':'false';
            $this->setEnv('APP_DEBUG',$now,$prev);
        }
        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }


}
