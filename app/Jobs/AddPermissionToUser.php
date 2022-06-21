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
        $total_like = $this->user->comment->sum('likes') + $this->user->marker->sum('likes');
        $total_dislike = $this->user->comment->sum('dislikes') + $this->user->marker->sum('dislikes');

        switch ($total_like) {
            case 10:
                $this->user->permission->attach(Permission::where("code", "change_state")->first());
                break;
            case 20:
                $this->user->permission->attach(Permission::where("code", "add_message")->first());
                break;
            case 30:
                $this->user->permission->attach(Permission::where("code", "add_message")->first());
                break;
            case 40:
                $this->user->permission->attach(Permission::where("code", "disable_marker")->first());
                $this->user->permission->attach(Permission::where("code", "edit_marker")->first());
                break;
        }

        $this->user->save();

        // switch ($total_dislike) {
        //     case 50:
        //         $this->user->permission->attach(Permission::where("code", "change_state")->first());
        //         break;
        // }
    }
}
