<?php

use App\Http\Controllers\BugController;
use App\Http\Controllers\RoundController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\VerticalController;
use App\Http\Controllers\HighestEducationValueController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectRoleController;
use App\Http\Controllers\OpportunityStatusController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\ProjectItemStatusController;
use App\Http\Controllers\ProjectItemController;
use App\Http\Controllers\UserTechnologyController;
use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTypeController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\UserWorkDetailController;
use App\Http\Controllers\RolePriceController;
use App\Http\Controllers\WorkerPriceController;
use App\Http\Controllers\ReleaseManagementController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DailyEntryController;
use App\Http\Controllers\StakeholderController;
use App\Http\Controllers\TaskCommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

// Add the custom route for the update1 method
Route::put('/profiles/{profile}/update1', [ProfileController::class, 'update1'])->name('profiles.update1');
// Route::resource('projects', ProjectsController::class);
// Route::get('projects/create', [ProjectsController::class, 'create'])->name('projects.create');

// Add the custom route for the update2 method
Route::put('/profiles/{profile}/update2', [ProfileController::class, 'update2'])->name('profiles.update2');

// Add the custom route for image deletion
Route::delete('/profiles/{profile}/delete-image', [ProfileController::class, 'deleteImage'])->name('profiles.deleteImage');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'project'], function () {
        Route::get('/', [ProjectsController::class, 'index'])->name('projects.index');
        Route::get('/create', [ProjectsController::class, 'create'])->name('projects.create');
        Route::get('/get-sprints', [ProjectsController::class, 'getSprints'])->name('getSprints');
        Route::get('/getTasksWithStatus', [ProjectsController::class, 'getTasksWithStatus'])->name('getTasksWithStatus');

        Route::post('/', [ProjectsController::class, 'store'])->name('projects.store');
        Route::get('/{project}', [ProjectsController::class, 'show'])->name('projects.show');
        Route::get('/{project}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
        Route::put('/{project}', [ProjectsController::class, 'update'])->name('projects.update');
        Route::get('/{project}/settings', [ProjectsController::class, 'settings'])->name('projects.settings');
        Route::put('/{project}/settings', [ProjectsController::class, 'updateSettings'])->name('projects.updateSettings');
        Route::delete('/{project}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
        Route::put('/{project}/cost', [ProjectsController::class, 'updateCost'])->name('projects.updateCost');
        Route::get('/{project}/cost', [ProjectsController::class, 'viewCost'])->name('projects.cost');
        Route::get('/{project}', [ProjectsController::class, 'sidebar'])->name('projects.sidebar');
        Route::get('/{project}/overview', [ProjectsController::class, 'overview'])->name('projects.overview');
        Route::get('/{project}/team', [ProjectsController::class, 'team'])->name('projects.team');
        Route::get('/{project}/sprint', [ProjectsController::class, 'sprint'])->name('projects.sprint');
        Route::get('/{project}/all-tasks', [ProjectsController::class, 'all_tasks'])->name('projects.all-tasks');
        Route::get('/{project}/daily_entry', [ProjectsController::class, 'daily_entry'])->name('projects.daily_entry');
        Route::get('/{project}/qa', [ProjectsController::class, 'qa'])->name('projects.qa');
        Route::get('/{project}/meetings', [ProjectsController::class, 'meetings'])->name('projects.meetings');
        Route::get('/{project}/documents', [ProjectsController::class, 'documents'])->name('projects.documents');
        Route::get('/{project}/release_management', [ProjectsController::class, 'release_management'])->name('projects.release_management');
        Route::get('/{project}/reports', [ProjectsController::class, 'reports'])->name('projects.reports');
        Route::any('/profile/get_images', [ProjectsController::class, 'getImages'])->name('projects.getImages');
       
       
        Route::post('/update-task-status', [ProjectsController::class, 'updateTaskStatus'])->name('update-task-status');
        Route::get('/get-documents/{project}', [ProjectsController::class, 'getDocuments'])->name('getDocuments');

        // Route::get('/project/{project}/documents', [ProjectsController::class, 'documents'])->name('projects.documents');

    });

    Route::post('/{project}/release_management', [ReleaseManagementController::class, 'store'])->name('projects.release_management.store');
    Route::put('/{project}/release_management/{releaseManagement}', [ReleaseManagementController::class, 'update'])->name('projects.release_management.update');

    Route::post('/project/{project}/release_management/{releaseManagement}/add-stakeholder', [ReleaseManagementController::class, 'addStakeholder'])->name('projects.release_management.addStakeholder');

    Route::resource('stakeholders', StakeholderController::class);

    // Route::resource('comments', TaskCommentController::class);

    Route::group(['prefix' => 'vertical'], function () {
        Route::get('/', [VerticalController::class, 'index'])->name('verticals.index');
        Route::get('/create', [VerticalController::class, 'create'])->name('verticals.create');
        Route::post('/', [VerticalController::class, 'store'])->name('verticals.store');
        Route::get('/{vertical}', [VerticalController::class, 'show'])->name('verticals.show');
        Route::get('/{vertical}/edit', [VerticalController::class, 'edit'])->name('verticals.edit');
        Route::put('/{vertical}', [VerticalController::class, 'update'])->name('verticals.update');
        Route::delete('/{vertical}', [VerticalController::class, 'destroy'])->name('verticals.destroy');
    });

    Route::delete('documents/{version}/delete', 'DocumentController@deleteVersion')->name('documents.delete');

    Route::resource('documents', DocumentController::class);

    Route::resource('highest-education-values', HighestEducationValueController::class);

    Route::resource('role-prices', RolePriceController::class);

    Route::resource('worker-prices', WorkerPriceController::class);

    Route::resource('project_members', ProjectMemberController::class);

    Route::resource('project-roles', ProjectRoleController::class);

    Route::resource('opportunity_status', OpportunityStatusController::class);
    Route::resource('opportunities', OpportunityController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('profiles', ProfileController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('user_work_details', UserWorkDetailController::class);

    Route::post('/get-tasks/{projectId}', [UserWorkDetailController::class, 'getTasksForProject']);


    Route::resource('technologies', TechnologyController::class);
    Route::resource('sprints', SprintController::class);
    Route::get('/exports', [SprintController::class, 'export'])->name('sprints.export');
    Route::resource('project_item_statuses', ProjectItemStatusController::class);
    Route::resource('project-items', ProjectItemController::class);
    Route::resource('user_technologies', UserTechnologyController::class);

    Route::get('/get-profile-email/{id}', 'ProfileController@getProfileEmail');

    Route::resource('task_types', TaskTypeController::class);

    Route::resource('task_status', TaskStatusController::class);

    Route::get('/kanban/{projectId}', [KanbanController::class, 'showKanban'])->name('kanban');

    Route::prefix('tasks')->group(function () {
        Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit'); // Note the {task} parameter here
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
        Route::post('/{task}/comments', [TaskCommentController::class, 'store'])->name('task.comments.store');
        Route::put('/comments/{comment}', [TaskCommentController::class, 'update'])->name('task.comments.update');
        Route::delete('/comments/{comment}', [TaskCommentController::class, 'destroy'])->name('task.comments.destroy');
        Route::post('/{task}/comments/{comment}/reply', [TaskCommentController::class, 'reply'])->name('task.comments.reply');
    });
    
});

//Microsoft Authentication Route

Route::controller(MicrosoftController::class)->group(function () {

    Route::get('auth/microsoft', 'redirectToProvider')->name('auth.microsoft');

    Route::get('auth/microsoft/callback', 'handleProviderCallback')->name('auth.microsoft.callback');

});

Route::post('/dailyEntry', [DailyEntryController::class, 'dailyEntry'])->name("dailyEntry");

Route::post('/createRound', [RoundController::class, 'createRound'])->name('createRound');

Route::post('/editRound', [RoundController::class, 'editRound'])->name('editRound');

Route::post('/createBug', [BugController::class, 'createBug'])->name('createBug');

Route::post('/editBug', [BugController::class, 'editBug'])->name('editBug');

Route::get('/deleteBug/{bugId}', [BugController::class, 'deleteBug'])->name('deleteBug');

Route::get('/findSprintDetailsWithId/{sprintId}', [BugController::class, 'findSprintDetailsWithId'])->name('findSprintDetailsWithId');

Route::post('/createTaskFromBug', [BugController::class, 'createTaskFromBug'])->name('createTaskFromBug');

Route::post('/createTaskFromMultipleBug', [BugController::class, 'createTaskFromMultipleBug'])->name('createTaskFromMultipleBug');

Route::get('/deleteBugDocuments/{id}', [BugController::class, 'deleteBugDocuments'])->name('deleteBugDocuments');

//Route::get('/deleteProjectMember/{id}', [ProjectMemberController::class, 'destroy'])->name('deleteProjectMember');