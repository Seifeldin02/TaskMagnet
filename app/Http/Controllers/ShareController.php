<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function destroy(Share $share)
    {
        // Check if the authenticated user is the one the task was shared with
        if (Auth::id() !== $share->user_id) {
            return back()->withErrors(['error' => 'You do not have permission to remove this share.']);
        }

        $share->delete();

        return back()->with('success', 'Share removed successfully.');
    }
}