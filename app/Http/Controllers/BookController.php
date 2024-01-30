<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        $books = Book::select(['id', 'title', 'isbn', 'author', 'is_borrowed'])->get();

        return response()->json($books);
    }

    public function showAvailableBooks(): JsonResponse
    {
        $books = Book::select(['id', 'title', 'isbn', 'author', 'is_borrowed'])
            ->where("is_borrowed", true)
            ->get();

        return response()->json($books);
    }

    public function addBook(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'isbn' => 'required|string',
            'author' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Book::create($validator->validated());

        return response()->json([
            'message' => 'Book added successfully',
            'book' => $book
        ], 201);
    }

    public function removeBook(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book removed successfully']);
    }

    public function editBook(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'isbn' => 'string',
            'author' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $book->update($validator->validated());

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }

    public function borrowBook(Request $request, int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        if ($book->is_borrowed) {
            return response()->json(['error' => 'Book is already borrowed'], 400);
        }

        // TODO $user->borrowedBooks()->attach($book);
        // $book->update(['is_borrowed' => true]);
        // dump($book);

        $book->is_borrowed = true;
        $book->save();

        return response()->json(['message' => 'Book borrowed successfully']);
    }

    public function returnBook(Request $request, int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        if (!$book->is_borrowed) {
            return response()->json(['error' => 'Book is not borrowed'], 400);
        }

        // TODO $user->borrowedBooks()->detach($book);
        // $book->update(['is_borrowed' => false]);

        $book->is_borrowed = false;
        $book->save();

        return response()->json(['message' => 'Book returned successfully']);
    }
}
