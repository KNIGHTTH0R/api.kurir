<?php
namespace App\Transformer;

use Illuminate\Http\Request;

trait TraitIncludes
{
    private $includesEnabled = [];

    private function checkIncludes()
    {
        $request = Request::capture();

        if (preg_match('/^[a-zA-Z0-9_]+(,[a-zA-Z0-9_]+)*$/i', $request->includes)) {
            $includes = explode(',', $request->includes);
            foreach ($includes as $include) {
                if (in_array($include, $this->getIncludesOptions())) {
                    $this->includesEnabled[] = $include;
                }
            }
        }

        return $this;
    }

    private function isEnabledInclude($include)
    {
        return in_array($include, $this->includesEnabled);
    }

    private function getIncludesOptions()
    {
        return [];
    }
}