<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ItemService;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    /**
     * @var ItemService
     */
    public $itemService;

    /**
     * @var Validator
     */
    public $validator;

    /**
     * ItemsController constructor.
     *
     * @param ItemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
        $this->validator = app('validator');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO: borrowed exists..
        $validator = $this->validator->make($request->all(), [
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
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        $result = $this->itemService->store($request->all());

        return response()->json($result->toArray());
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = $this->validator->make($request->all(), [
            'name'          => 'sometimes',
            'details'       => 'sometimes',
            'amount'        => 'sometimes|integer',
            'type'          => 'sometimes',
            'return_at'     => 'sometimes|date',
            'returned_at'   => 'sometimes|date',
            'borrowed_at'   => 'sometimes|date',
            'borrowed_to'   => 'sometimes',
            'borrowed_from' => 'sometimes',
        ]);
        if ($validator->fails()) {
            return response()->json([ 'message' => $validator->messages() ], 400);
        }

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

function parseContent($content)
{
    $lines = explode("\r\n", $content);
    $result = [];
    $contentType = explode('; ', $lines[ 0 ]);
    $result[ 'content-type' ] = explode(': ', array_pop($contentType))[ 1 ];
    foreach ($contentType as $item) {
        list($paramName, $paramValue) = explode('=', $item);
        $result[ $paramName ] = trim($paramValue, '"');
    }

}