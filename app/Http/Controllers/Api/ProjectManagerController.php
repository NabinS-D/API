<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectNoteResource;
use App\Notifications\ProjectAdded;
use App\Events\ProjectAddedEvent;


class ProjectManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('tasks','tasks.files', 'tasks.users')->paginate(5);

        if ($projects === null)  
        {
            return response()->json(['message' =>'No projects found'],200);
        }
        else
        {
            return ProjectResource::collection($projects);
        }   
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'project_name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json([$validator->errors()],400);
        }
        else
        {
            $projectname = $request->input('project_name');
            $description = $request->input('description');
            $projectadd = new Project();
            $projectadd->project_name = $projectname;
            $projectadd->description = $description;
    
            if($projectadd->save())
            {
                $users = User::all();
                foreach($users as $user)
                {
                    event(new ProjectAddedEvent($projectadd,$user));
                }
  

                return response()->json(['message' => 'Project added successfully', 'Project' => $projectadd], 201);
    
            }
            else
            {
                return response()->json(['message' => 'Project add failed'],500);
            }
        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with('tasks','tasks.files', 'tasks.users')->find($id);

        if ($project === null) 
        {
            return response()->json(['message' =>'Project not found for ID: '.$id],404);
        }
        else
        {
            return new ProjectResource($project);
        }  

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            $project = Project::find($id);
            if(is_null($project))
            {
                return response()->json(['message' =>'Project not found for ID: '.$id],404);
            }     
            else
            {   
                DB::beginTransaction();
                try
                {
                    $validator = Validator::make($request->all(),[
                        'project_name' => 'required',
                        'description' => 'required',
                        'note' => 'required',
                    ]);
                    if ($validator->fails())
                    {
                        return response()->json([$validator->errors()],400);
                    }
                    // Update the project
                    $project->project_name = $request['project_name'];
                    $project->description  = $request['description'];
                    $project->save();

                     // Create a new note
                     $note = new ProjectNote();
                     $note->project_id = $id;
                     $note->description = $request['note'];
                     $note->save();

                     DB::commit();
                     return response()->json(['message' => 'The selected project has been updated successfully'], 200);

                }
                catch(Exception $e)
                {
                    DB::rollBack(); 
                    return response()->json(['message' => $e->getMessage()],500);
                }
            }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::find($id);
            if(is_null($project))
            {
                return response()->json(['message' =>'Project not found for ID: '.$id],404);
            } 
            else
            {
                DB::beginTransaction();
                try
                {
                    $project->delete();
                    db::commit();
                    return response()->json(['message'=> 'The selected project has been successfully deleted.'],200);
                }
                catch(Exception $e)
                {
                    DB::rollBack();
                    return response()->json(['message'=> $e->getMessage()],500);
                }
    
            }
    }

    public function shownotes($id)
    {
        $project = Project::with('notes')->find($id);
        if(is_null($project))
        {
            return response()->json(['message' =>'Project not found for ID: '.$id],404);
        }
       else
       {
            if($project->notes->isEmpty())
            {
                return response()->json(['message' =>'No notes yet for this project'], 200);
            }
            else
            {
                return new ProjectNoteResource($project);
            }  
       }
               
    }
    
    
}
