<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GraphQL\Client;
use App\Libraries\RouteDiscovery\Attributes\Route;
use App\Libraries\RouteDiscovery\Attributes\Prefix;
use GraphQL\Exception\QueryError;
use GraphQL\SchemaObject\client_bool_expInputObject;
use GraphQL\SchemaObject\RootClientArgumentsObject;
use GraphQL\SchemaObject\RootClientPinArgumentsObject;
use GraphQL\SchemaObject\RootClientPinByPkArgumentsObject;
use GraphQL\SchemaObject\RootQueryObject;
use GraphQL\SchemaObject\String_comparison_expInputObject;

class TestController extends Controller
{

    #[Route(method: 'get', uri: 'graphql')]
    public function index(Request $request)
    {
        // Create Client object to contact the GraphQL endpoint
        $client = new Client(
            'http://graphql-engine:8080/v1/graphql',
            []
        );

        // Create root query object
        $queryRoot = new RootQueryObject();

        $queryRoot = $queryRoot->selectClient((new RootClientArgumentsObject())
        ->setWhere((new client_bool_expInputObject())
        ->setUsername((new String_comparison_expInputObject())->setEq('my-email@hasura.com'))))
        ->selectUsername()
        ->selectActive();

        try {
            $results = $client->runQuery($queryRoot->getQuery());
        }
        catch (QueryError $exception) {
            $results = $exception->getErrorDetails();
        }

        return response()->json($results->getData());
    }

    public function test(Request $request)
    {
        return response($request->all());
    }
}
