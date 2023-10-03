<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\AgentProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class AgentProductController extends Controller
{

    public function scopedAgentProducts()
    {
        return DB::table('agent_products')
            ->join('agents', 'agent_products.agent_id', '=', 'agents.agent_id')
            ->join('products', 'agent_products.product_id', '=', 'products.product_id')
            ->select('agent_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'agents.longitude', 'agents.latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names', 'agents.verified', 'agents.availability', 'agents.profile_image_url')
            ->where('agents.agent_id', request('agent_id'))
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $AgentProduct = AgentProduct::where('product_id', request('product_id'))
            ->where('agent_id', request('agent_id'))
            ->first();
        if ($AgentProduct) {
            return Response::json(array(
                'already_exists' => true,
            ));
        }
        $agent_product_id = Str::uuid();
        AgentProduct::forceCreate(
            ['agent_product_id' => $agent_product_id] +
            $request->all());

        $agent_products = DB::table('agent_products')
            ->join('agents', 'agent_products.agent_id', '=', 'agents.agent_id')
            ->join('products', 'agent_products.product_id', '=', 'products.product_id')
            ->select('agent_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'agents.longitude', 'agents.latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names', 'agents.verified', 'agents.availability', 'agents.profile_image_url')
            ->where('agent_product_id', $agent_product_id)
            ->get();

        return Response::json(array(
            'agent_products' => $agent_products,
        ));
    }

    public function proximityAgentProducts(Request $request)
    {
        return DB::table('agent_products')
            ->join('agents', 'agent_products.agent_id', '=', 'agents.agent_id')
            ->join('products', 'agent_products.product_id', '=', 'products.product_id')
            ->select('agent_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'agents.longitude', 'agents.latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names', 'agents.verified', 'agents.availability', 'agents.profile_image_url')
            ->where('products.product_id', request('product_id'))
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgentProduct  $agentProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgentProduct $agentProduct)
    {
        $AgentProduct = AgentProduct::where('product_id', request('product_id'))
            ->where('agent_id', $agentProduct->agent_id)
            ->where('agent_product_id', '!=', $agentProduct->agent_product_id)
            ->first();
        if ($AgentProduct) {
            return Response::json(array(
                'already_exists' => true,
            ));
        }
        $agentProduct->update($request->all());

        $agent_products = DB::table('agent_products')
            ->join('agents', 'agent_products.agent_id', '=', 'agents.agent_id')
            ->join('products', 'agent_products.product_id', '=', 'products.product_id')
            ->select('agent_products.*', 'products.name AS product_name', 'products.image_url AS product_image_url', 'agents.longitude', 'agents.latitude', 'agents.title', 'agents.first_name', 'agents.last_name', 'agents.other_names', 'agents.verified', 'agents.availability', 'agents.profile_image_url')
            ->where('agent_product_id', $agentProduct->agent_product_id)
            ->get();

        return Response::json(array(
            'agent_products' => $agent_products,
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgentProduct  $agentProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgentProduct $agentProduct)
    {
        $status = $agentProduct->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
