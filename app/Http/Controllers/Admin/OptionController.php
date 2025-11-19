<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{

  public function store(Request $request)
  {
    $option = Option::create($request->only(['content', 'question_id']));
    $this->setOrder($option);

    return response()->json(['option' => $option], 200);
  }
  //??=========================================================================================================
  public function show(Option $option)
  {
    return response()->json(['option' => $option], 200);
  }
  //??=========================================================================================================
  public function edit($id)
  {
    $option = Option::findOrFail($id);
    return response()->json(['option' => $option], 200);
  }
  //??=========================================================================================================
  public function update(Request $request, Option $option)
  {
    $option->update($request->only(['content', 'question_id']));
    return response()->json(['option' => $option, 'message' => trans('translation.Updated successfully')], 200);
  }
  //??=========================================================================================================
  public function setOrder($option)
  {
    $question = $option->question;
    $option->update(['order' => $question->options->count()]);
  }
}
