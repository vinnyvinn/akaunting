<?php
Route::group([
    'middleware' => [
        'admin'
    ],
    'namespace' => 'Modules\Projects\Http\Controllers'
], function () {
    Route::prefix('projects')->group(function () {
        Route::resource('projects', 'ProjectsProjectController', [
            'as' => 'projects'
        ]);

        Route::resource('tasks', 'ProjectsTaskController', [
            'as' => 'projects'
        ]);

        Route::resource('subtasks', 'ProjectsSubtaskController', [
            'as' => 'projects'
        ]);

        Route::resource('discussions', 'ProjectsDiscussionController', [
            'as' => 'projects'
        ]);

        Route::resource('comments', 'ProjectsCommentController', [
            'as' => 'projects'
        ]);

        Route::resource('likes', 'ProjectsDiscussionLikeController', [
            'as' => 'projects'
        ]);

        Route::get('/projects/discussions/{discussion}/likes', 'ProjectsDiscussionController@likes')->name('projects.discussions.likes');
        Route::get('/projects/discussions/{discussion}/comments', 'ProjectsDiscussionController@comments')->name('projects.discussions.comments');
        Route::get('/export/transactions/{project}', 'ProjectsProjectController@exportTransactions')->name('projects.transactions.export');
        Route::get('/chart/profitloss/{project}', 'ProjectsProjectController@profitLoss');
        Route::get('/projects/{project}/print', 'ProjectsProjectController@printProject');
        Route::post('/subtasks/adjustorder', 'ProjectsSubtaskController@adjustOrder');
        Route::post('/subtasks/complete/{subtask}', 'ProjectsSubtaskController@makeComplete');
        Route::post('/subtasks/notstarted/{subtask}', 'ProjectsSubtaskController@makeNotStarted');
    });
});
