<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Notifier;
use Auth;

class NotifiersController extends Controller
{
    public function index()
    {
        return Auth::user()->notifiers()->get()->map(function ($item) {
            $item->label = ucwords($item->type);

            return $item;
        });
    }

    public function store(Requests\NewNotifierRequest $request)
    {
        $notifier = new Notifier($request->all());
        Auth::user()->notifiers()->save($notifier);
    }

    public function destroy(\Illuminate\Http\Request $request)
    {
        $validator = \Validator::make([
            'notifier.type'  => 'required',
            'notifier.value' => 'required',
        ], $request->all());
        if ($validator->fails()) {
            abort(500, 'validator_exception');
        }

        $notifier = $request->get('notifier');
        $type = $notifier['type'];
        $value = $notifier['value'];

        $notifier = Notifier::where('type', $type)
                            ->where('value', $value)
                            ->where('user_id', Auth::user()->id)
                            ->first();

        if (!$notifier) {
            abort(500, 'missing_notifier');
        }

        $notifier->delete();
    }
}
