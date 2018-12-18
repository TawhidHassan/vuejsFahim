<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResourec;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*return CustomerResourec::collection(Customer::latest()->paginate(10));*/ /*coustomaige api data view*/ /*Resource is use for costomaige the table colam name for one data display in fontend*/

        return new CustomerCollection(Customer::orderBy('id','DESC')->paginate(5)); /*for api all data details*/ /*collection is for view data in butifull formet and is use for costomaige the table colam name for all collection of data display in fontend*/
    }
   public function search($field,$query)
   {
       return new CustomerCollection(Customer::where($field,'LIKE',"%$query%")->latest()->paginate(10));

   }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:customers',
            'phone'=>'required|numeric',
            'address'=>'required',
            'total'=>'required',
        ]);
        $customer=new Customer();
        $customer->name=$request->name;
        $customer->email=$request->email;
        $customer->phone=$request->phone;
        $customer->address=$request->address;
        $customer->total=$request->total;
        $customer->save();
        return new CustomerResourec($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new CustomerResourec(Customer::findOrFail($id));
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

        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:customers,email,'.$id,
            'phone'=>'required|numeric',
            'address'=>'required',
            'total'=>'required',
        ]);
        $customer=Customer::findOrfail($id);
        $customer->name=$request->name;
        $customer->email=$request->email;
        $customer->phone=$request->phone;
        $customer->address=$request->address;
        $customer->total=$request->total;
        $customer->update();
        return new CustomerResourec($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $customer=Customer::findOrfail($id);
       $customer->delete();
       return new CustomerResourec($customer);
    }
}
