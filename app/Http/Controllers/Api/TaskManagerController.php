<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\File;
use App\Models\Note;
use App\Models\TaskUser;
use App\Models\ProjectTask;
use App\Http\Resources\TaskResource;
use App\Http\Resources\NoteResource;
use App\Http\Resources\UserResource;
use App\Events\TaskAddedEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskAdd;

class TaskManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('files', 'projects')->paginate(5);

        if ($tasks === null)  
        {
            return response()->json(['message' =>'No tasks found'],200);
        }
        else
        {
            return TaskResource::collection($tasks);
        }   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['priority' => $request->get('priority', 'No')]);

        $validator = Validator::make($request->all(),[
            'task' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'FILE.*' => 'mimes:jpg,jpeg,pdf,csv|max:2048',
            'selectedProjects' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json([$validator->errors()],400);
        }
        else
        {   
            DB::beginTransaction();
            try
            { 
                  $data = $request->input();
                  //tasks table
                  $task = new Task();
                  $task->task = $data['task'];
                  $task->description = $data['description'];
                  $task->priority = $data['priority'];
                  $task->save();
                  $lastInsertedId = $task->task_id;   //last inserted task id
                      
                  //files table operation
                  if($request->hasFile('FILE')) 
                  {    
                      $fileIDs = array();
                      foreach($request->file('FILE') as $file) 
                      {   
                      
                          $filename = $file->getClientOriginalName();
                          $file->move(public_path('uploads'),$filename);
        
                          // Insert the file info into the database
                          $fileRecord = new File();
                          $fileRecord->file_name = $filename;
                          $fileRecord->file_loc = 'uploads/'.$filename;
                          $fileRecord->task_id =  $lastInsertedId;
                          $fileRecord->save();
                          $fileIDs[] = $fileRecord->file_id;   //last inserted file id     
                      }
                    
                  }
      
                        //Insert intp project_task table
                        
                            foreach ($request['selectedProjects'] as $projectname)
                            {

                                $project_selected = Project::where('project_name',$projectname)->first();
                                    if($project_selected)
                                    {
                                        $project_tasks = new ProjectTask();
                                        $project_tasks->project_id = $project_selected->project_id;
                                        $project_tasks->task_id =  $lastInsertedId;
                                        $project_tasks->save();
                                    }
                                    else
                                    {
                                        return response()->json(['message' =>'Selected project: '.$projectname.' not found. '],404);
                                    }
                                  
                            }
                        
                      
                  //Insert into task_user table
                  if($request->has('selectedUsers'))
                  {     
                      
                      foreach ($request['selectedUsers'] as $user)
                      {
                          $user_selected = User::where('name',$user)->first();
                          if( $user_selected )
                          {
                            $task_users = new TaskUser();
                            $task_users->id = $user_selected->id;
                            $task_users->task_id =   $lastInsertedId;
                            $task_users->save();
                          }
                          else
                          {
                            return response()->json(['message' =>'Selected User: '.$user.' not found. '],404);

                          }
                     
                        
                      }
                  }

                  DB::commit();

                  // Assuming $task is the task you are assigning to users
                    $users = $task->users; // Collection of users assigned to the task

                    foreach ($users as $user) {
                        // Dispatch the event for each user
                        event(new TaskAddedEvent($task, $user));
                        
                        
                    }

                  return response()->json([
                          'message' =>'Task and its details added successfully',
                          'Task detail' => new TaskResource($task),       
                  ],201);
            }
            catch (\Exception $e) 
            {
                DB::rollBack();
                return response()->json(['message'=> $e->getMessage()],500);
            }
            
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      
        $tasks = Task::with('files', 'projects')->find($id);

        if ($tasks === null)  
        {
            return response()->json(['message' =>'No task found'],200);
        }
        else
        {
            return new TaskResource($tasks);
        }   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $task = Task::find($id);
       if ($task === null)
       {
        return response()->json(['message'=> 'Task not found'],404);    
       }
       else
       {   
            DB::beginTransaction();
            try
            {   $request->merge(['priority' => $request->get('priority', 'No')]);
                $validator = Validator::make($request->all(),[
                    'task' => 'required',
                    'description' => 'required',
                    'priority' => 'required',
                    'note' => 'required',
                ]);

                if ($validator->fails())
                {
                    return response()->json([$validator->errors()],400);
                }
                    //Update task
                    $task->task = $request['task'];
                    $task->description = $request['description'];
                    $task->priority = $request['priority'];
                    $task->save();

                    //Insert note in the notes table
                    $note = new Note();
                    $note->task_id = $id;
                    $note->description = $request['note'];
                    $note->save();

                    db::commit();
                    return response()->json(['message'=> 'The selected task has been successfully updated'],200);

            }
            catch (\Exception $e)
            {
                DB::rollBack();
                return response()->json(['message'=> $e->getMessage()],500);
            }
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if(is_null($task))
        {
            return response()->json(['message'=> 'Task not found'],404);
        }
        else
        {
            DB::beginTransaction();
            try
            {
                $task->delete();
                db::commit();
                return response()->json(['message'=> 'The selected task has been successfully deleted'],200);
            }
            catch (\Exception $e)
            {
                DB::rollBack();
                return response()->json(['message'=> $e->getMessage()],500);
            }
          
        }

    }

    public function shownotes($id)
    {
        $task = Task::with('notes')->find($id);
        if(is_null($task))
        {
            return response()->json(['message'=> 'Task doesnt exist'],404);
        }
       else
       {
            if($task->notes->isEmpty())
            {
                return response()->json(['message' =>'No notes yet for this task'], 200);
            }
            else
            {
                return new NoteResource($task);
            }  
       }
                
    }

    public function userdashboard()
    {
        $users = User::with('tasks', 'tasks.files')->get();
        return UserResource::collection($users);

    }
}
