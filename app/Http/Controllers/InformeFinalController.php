<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformeFinalStoreRequest;
use App\Services\InformeFinalService;

class InformeFinalController extends Controller
{
  public function __construct(
      private InformeFinalService $informeFinalService
    ){}

    public function store(InformeFinalStoreRequest $request){
      $response = $this->informeFinalService->store($request->data);
      $jsonDecode = json_decode($response->content());

      if ($jsonDecode->code === 200) {
        return back()->with('success', $jsonDecode->message);
      }

      if ($jsonDecode->code === 500) {
        return back()->with('error', $jsonDecode->message);
      }
    }
}
