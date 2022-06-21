<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Permission;

class AddPermissionToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    public function __construct($user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $user = $this->user;
        $total_like = $user->comment->sum('likes') + $user->marker->sum('likes');
        $total_dislike = $user->comment->sum('dislikes') + $user->marker->sum('dislikes');

        switch ($total_like) {
            case 10:
                $user->permission->attach(Permission::where("code", "change_state")->first());
                break;
            case 20:
                $user->permission->attach(Permission::where("code", "add_message")->first());
                break;
            case 30:
                $user->permission->attach(Permission::where("code", "add_message")->first());
                break;
            case 40:
                $user->permission->attach(Permission::where("code", "disable_marker")->first());
                $user->permission->attach(Permission::where("code", "edit_marker")->first());
                break;
        }

        $user->save();

        // switch ($total_dislike) {
        //     case 50:
        //         $this->user->permission->attach(Permission::where("code", "change_state")->first());
        //         break;
        // }
    }
}
