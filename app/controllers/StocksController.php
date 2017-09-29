<?php

class StocksController extends \BaseController {

	/**
	 * Display a listing of stocks
	 *
	 * @return Response
	 */
	public function index()
	{
		$stocks = Stock::all();

		$items = Item::all();

		$stock_in = DB::table('stocks')
         ->join('items', 'stocks.item_id', '=', 'items.id')
         ->get();

        if (! Entrust::can('view_stock') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		return View::make('stocks.index', compact('stocks', 'items','stock_in'));
	}
	}

	/**
	 * Show the form for creating a new stock
	 *
	 * @return Response
	 */
	public function create()
	{
		$items = Item::all();
		$locations = Location::all();
		$clients = Client::all();
		$erporders = DB::table('erporders')
		                 ->join('clients','erporders.client_id','=','clients.id')
		                 ->select( DB::raw('erporders.client_id, erporders.order_number'))
		                 ->get();

        if (! Entrust::can('receive_stock') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		return View::make('stocks.create', compact('items', 'locations','clients','erporders'));
	}
	}

	public function confirmstock()
	{
		$items = Item::all();
		$locations = Location::all();
		$clients = Client::all();
		$erporders = DB::table('erporders')
		                 ->join('clients','erporders.client_id','=','clients.id')
		                 ->select( DB::raw('erporders.client_id, erporders.order_number'))
		                 ->get();

        if (! Entrust::can('receive_stock') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		return View::make('stocks.create', compact('items', 'locations','clients','erporders'));
	}
	}

	/**
	 * Store a newly created stock in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Stock::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$item_id = Input::get('item');
		$location_id = Input::get('location');
		$date = date('Y-m-d');
		$item = Item::findOrFail($item_id);
		$location = Location::find($location_id);
		$quantity = Input::get('quantity');
		/*$date = Input::get('date');*/

		

		Stock::addStock($item, $location, $quantity, $date);

		if (! Entrust::can('confirm_stock') ) // Checks the current user
        {
        return Redirect::to('stocks')->with('notice', 'Stock has been successfully updated! Please wait for admin confirmation....');

        }else{

		return Redirect::route('stocks.index')->withFlashMessage('stock has been successfully updated!');
	}
	}

	/**
	 * Display the specified stock.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$stock = Stock::findOrFail($id);

        if (! Entrust::can('view_stock') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		return View::make('stocks.show', compact('stock'));
	}
    }

	/**
	 * Show the form for editing the specified stock.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$stock = Stock::find($id);

		return View::make('stocks.edit', compact('stock'));
	}

	/**
	 * Update the specified stock in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$stock = Stock::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Stock::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$stock->update($data);

		return Redirect::route('stocks.index');
	}

	/**
	 * Remove the specified stock from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Stock::destroy($id);

		return Redirect::route('stocks.index');
	}

}
