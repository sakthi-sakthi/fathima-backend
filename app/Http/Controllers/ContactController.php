<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactuserFormMail;
use Throwable;
use App\Models\Log;
use App\Models\Option;
class ContactController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::all();
            return view('admin.contact.index',compact('contacts'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'contact',
                'message' => 'contact page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'contact page could not be loaded.']);
        }

       
        
    }
    public function sendmail($id){
        $send = Contact::where('id',$id)->first();
        $email = $send['email'];
         $bodyContent = [
             'toName' => $send['name'],
             ];
         {  
             try {
                Mail::to($email)->send(new ContactuserFormMail($bodyContent));
                return redirect()->back()->with(['type' => 'success', 'message' =>'Email sent Successfully.']);
                }
                 catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['type' => 'error', 'message' =>'Email could not be sent.']);
             }
         } 
         
    }
    public function delete($id){ 
        $contact =Contact::where('id' ,$id)->first();
        $contact->delete();
        return redirect()->route('admin.contact.index')->with(['type' => 'success', 'messages' => 'Contact details are deleted successfully']);
        
    }
    public function show(){
        
        try {
            $contacts = Contact::onlyTrashed()->get();
            return view('admin.contact.trash',compact('contacts'));
        } catch (Throwable $th) {
            Log::create([
                'model' => 'contacts',
                'message' => 'contacts trash page could not be loaded.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'contacts trash page could not be loaded.']);
        }
    }
    public function recover($id){
    
        try {
            Contact::onlyTrashed()->find($id)->restore();
            return redirect()->route('admin.contact.index')->with(['type' => 'success', 'messages' => 'Contact details are recovered successfully']);

        } catch (Throwable $th) {
            Log::create([
                'model' => 'contact',
                'message' => 'The contact could not be recovered.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            
            return redirect()->back()->with(['type' => 'error', 'message' =>'The contact could not be recovered.']);
        }

    }
    public function destroy($id){
            
        
        try {
            $contact = Contact::withTrashed()->find($id);
            $contact->delete();
            $contact->forceDelete();
            return redirect()->route('admin.contact.trash')->with(['type' => 'warning', 'message' =>'Post Deleted.']);
        } catch (Throwable $th) {
            Log::create([
                'model' => 'contact',
                'message' => 'The contact could not be destroyed.',
                'th_message' => $th->getMessage(),
                'th_file' => $th->getFile(),
                'th_line' => $th->getLine(),
            ]);
            return redirect()->back()->with(['type' => 'error', 'message' =>'The contact could not be destroyed.']);
        }
    }
}
