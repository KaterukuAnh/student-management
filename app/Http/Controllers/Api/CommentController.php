<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Nhận xét của giáo viên về chính học sinh đang đăng nhập.
    public function index(Request $request)
    {
        $studentId = $request->user()->student_id;

        abort_if(! $studentId, 404, __('Tài khoản chưa liên kết với hồ sơ học sinh.'));

        $comments = Comment::with('teacher')
            ->where('student_id', $studentId)
            ->latest()
            ->get()
            ->map(fn (Comment $comment) => [
                'id' => $comment->id,
                'teacher' => $comment->teacher->name,
                'conduct' => $comment->conduct,
                'conduct_label' => __(Comment::CONDUCTS[$comment->conduct] ?? $comment->conduct),
                'content' => $comment->content,
                'created_at' => $comment->created_at->toIso8601String(),
            ]);

        return response()->json(['data' => $comments]);
    }
}
