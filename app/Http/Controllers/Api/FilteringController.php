<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Http\Resources\FilterByUserResource;
use App\Http\Resources\FilterByProjectResource;
use App\Http\Resources\FilteredDataResource;
use App\Http\Resources\TaskResource;


class FilteringController extends Controller
{
    public function displaytasks()
    {
        $tasks = Task::with('users', 'projects')->get();
        return TaskResource::collection($tasks);
    }

    public function filter(Request $request)
    {
        // Get the selected user and project from the request
        $selectedUser = $request['selecteduserID'];
        $selectedProject = $request['selectedprojectID'];
    
        // Initialize the variables to hold the results
        $userfilter = null;
        $projectfilter = null;
        $taskfilter = null;
        $tasks = null;
    
        // If a user is selected and no project is selected, fetch the user with its associated Task, Project, and files
        if (!empty($selectedUser) && empty($selectedProject)) 
        {
            try 
            {
                $userfilter = User::with(['tasks','tasks.projects', 'tasks.files', 'tasks.users'])->findOrFail($selectedUser);
                if ($userfilter->tasks->isEmpty()) 
                {
                    return response()->json(['error' => 'No tasks found for the selected user.'], 404);
                }
                else
                {
                    return new FilterByUserResource($userfilter);
                }
              
            } 
            catch (ModelNotFoundException $e) 
            {
                return response()->json(['error' => 'User not found'],404);
            }
        }
        // If a project is selected and no user is selected, fetch the project with its associated Task, users, and files
        elseif (empty($selectedUser) && !empty($selectedProject)) 
        {
            try
            {
                $projectfilter = Project::with('tasks.projects', 'tasks.files', 'tasks.users')->findOrFail($selectedProject);
                if ($projectfilter->tasks->isEmpty()) 
                {
                    return response()->json(['error' => 'No tasks found for the selected project.'], 404);
                }
                else
                {
                    return new FilterByProjectResource($projectfilter);
                }
            }
            catch (ModelNotFoundException $e)
            {
                return response()->json(['error'=> 'Project not found'],404);
            }
        }
        // If both a user and a project are selected, filter Task based on the selected user and project
        elseif (!empty($selectedUser) && !empty($selectedProject)) 
        {
            try
            {
                $taskfilter = Task::whereHas('users', function ($query) use ($selectedUser) {
                $query->where('users.id', $selectedUser);
            })->whereHas('projects', function ($query) use ($selectedProject) {
                $query->where('projects.project_id', $selectedProject);
            })->get();

                if ($taskfilter->isEmpty()) 
                {
                    return response()->json(['error' => 'No tasks found for the given user and project'], 404);
                }
                else
                {
                    $selectedUserName = User::find($selectedUser)->name;
                    $selectedProjectName = Project::find($selectedProject)->project_name;
                    
                    return response()->json([
                        'Selected User' => $selectedUserName,
                        'Selected Project' => $selectedProjectName,
                        'Tasks' => FilteredDataResource::collection($taskfilter)
                    ]);
                }   
            }
            catch (ModelNotFoundException $e)
            {
                return response()->json(['error'=> 'Invalid Project or User'],404);
            }
        }
        else
        {
            $tasks = Task::with('users', 'projects')->get();
            return TaskResource::collection($tasks);
        }
    }
}
