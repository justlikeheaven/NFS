<?php
namespace app\controller;

class Index extends Controller{

	public function _init(){
	    
	}
	
	public function index(){
	    
	    $order = Order::getList(21);
	    
	    View::display($order);
	    
	}
	

    
}