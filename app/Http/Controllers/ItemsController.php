<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    /**
     * @var ItemService
     */
    public $itemService;

    /**
     * ItemsController constructor.
     *
     * @param ItemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->itemService->paginate();
    }

    public function user(Request $request)
    {
        return $this->itemService->paginateUserItems($request->user(), $request->get('per_page', 6));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request): JsonResponse
    {
        //TODO: borrowed exists..
        $this->validate($request, [
            'name'          => 'required',
            'details'       => 'sometimes',
            'amount'        => 'sometimes|integer',
            'type'          => 'required',
            'return_at'     => 'required|date',
            'borrowed_at'   => 'sometimes|date',
            'borrowed_to'   => 'required',
            'borrowed_from' => 'required',
            'image'         => 'sometimes|image',
        ]);

        $data = array_add($request->all(), 'owner_id', auth()->user()->id);

        $result = $this->itemService->store($data);

        return response()->json($result->toArray());
    }

    public function setImage($id, Request $request): JsonResponse
    {
        $this->validate($request, [
            'image' => 'required|image',
        ]);

        $item = $this->itemService->setImage($id, $request->file('image'));

        return response()->json(array_only($item->toArray(), [ 'id', 'image' ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->itemService->find($id);

        return response()->json($item->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'          => 'sometimes',
            'details'       => 'sometimes',
            'amount'        => 'nullable|sometimes|integer',
            // FIXME: what are all types?
            'type'          => 'sometimes',
            'return_at'     => 'sometimes|date',
            'returned_at'   => 'nullable|sometimes|date',
            'borrowed_at'   => 'sometimes|date',
            'borrowed_to'   => 'sometimes',
            'borrowed_from' => 'sometimes',
        ]);

        $result = $this->itemService->update($id, $request->all());

        return response()->json($result->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}