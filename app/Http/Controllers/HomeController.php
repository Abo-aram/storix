<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\JwtHelper;

class HomeController extends Controller
{
    use JwtHelper;



    public function index(){
        return view('components.home');
    }
    public function home(Request $request){
        $request->validate([
            'type' => 'nullable|string',
            'sort' => 'nullable|string',
            'search' => 'nullable|string',
            'folder_id' => 'nullable',
            'loadedFilesId' => 'nullable|array',
            'newFileAdded' => 'nullable|boolean',
        ]);
        // creating a log message for debugging
        Log::info('HomeController@home called', [
            'type' => $request->type,
            'sort' => $request->sort,
            'search' => $request->search,
            'folder_id' => $request->folder_id,
            'loadedfilesId' => $request->loadedfilesId,
            'newFileAdded' => $request->newFileAdded,
        ]);

        // log a message for debugging

        


         // Get the user from the request
        $user = $this->getUser($request);
        $files = collect();

        if($user){
            $query = $user -> files();

            if($request->type === 'image'){
                $query ->whereIn('extension',['jpg','jpeg','png','gif']);

            }else if($request->type === 'other'){
                $query ->whereNotIn('extension',['jpg','jpeg','png','gif']);
            }
        }
        if(!$request->newFileAdded){
            LOG::info('no files have been added');
                switch ($request->sort){
                case 'name_asc':
                    $query -> orderBy('original_name','asc');
                    break;
                case 'name_desc':
                    $query -> orderBy('original_name','desc');
                    break;
                case 'size_asc':
                    $query -> orderBy('size','asc');
                    break;
                case 'size_desc':
                    $query -> orderBy('size','desc');
                    break;
                case 'oddest':
                    $query -> orderBy('created_at','asc');
                    break;
                case 'newest':
                    $query -> orderBy('created_at','desc');
                    break;
                default:
            };

        
            if ($request->search) {
                $query->where('original_name', 'like', '%' . $request->search . '%');
            }

            if($request->folder_id){
                $query->where('folder_id', $request->folder_id);
            }


            //limit to 10 files
            // if the user already loaded the file based on id, then we will not load those files again
            if($request->loadedfilesId && count($request->loadedfilesId) > 0){
                $query->whereNotIn('id', $request->loadedfilesId);
            }
            // retriving most recet files in Created_at order or updated_at order
            $query->orderBy('updated_at', 'desc');
            

            $query->limit(12);

            $files = $query->get();

        }else{
            LOG::info('new file has been added');
            //this means user just added a file and we need to return that file which is the most recent file
            $query->orderBy('created_at', 'desc');
            $files = $query->first();
            LOG::info($files);


            
        }

        



        return response()->json($files);

        }
        
        
}
