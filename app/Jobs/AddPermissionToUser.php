<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Permission;
use Log;

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
        Log::info("Total likes: " . $total_like);
        switch (true) {
            case in_array($total_like, range(10, 19)):
                $user->permission->attach(Permission::where("code", "change_state")->first());
                Log::info("Change state permission added");
                break;
            case in_array($total_like, range(20, 29)):
                $user->permission->attach(Permission::where("code", "add_message")->first());
                Log::info("Add message permission added");
                break;
            case in_array($total_like, range(30, 39)):
                $user->permission->attach(Permission::where("code", "disable_marker")->first());
                $user->permission->attach(Permission::where("code", "edit_marker")->first());
                Log::info("Disable and edit marker permissions added");
                break;
            default:
                Log::info("Permission not updated");
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
