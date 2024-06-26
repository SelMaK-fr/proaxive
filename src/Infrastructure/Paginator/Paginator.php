<?php
/*
 * PHP Pagination Class
 *
 * @author David Carr - dave@daveismyname.com - http://www.daveismyname.com
 * @version 1.0
 * @date October 20, 2012
 */
namespace Selmak\Proaxive2\Infrastructure\Paginator;
use Psr\Http\Message\ServerRequestInterface;
use Selmak\Proaxive2\Paginator\sting;

class Paginator{

        /**
	 * set the number of items per page.
	 *
	 * @var numeric
	*/
	private $_perPage;

	/**
	 * set get parameter for fetching the page number
	 *
	 * @var string
	*/
	private $_instance;

	/**
	 * sets the page number.
	 *
	 * @var numeric
	*/
	private $_page;

	/**
	 * set the limit for the data source
	 *
	 * @var string
	*/
	private $_limit;

	/**
	 * set the total number of records/items.
	 *
	 * @var numeric
	*/
	private $_totalRows = 0;
    private ServerRequestInterface $request;


    /**
	 *  __construct
	 *  
	 *  pass values when class is istantiated 
	 *  
	 * @param numeric  $_perPage  sets the number of iteems per page
	 * @param numeric  $_instance sets the instance for the GET parameter
	 */
	public function __construct($perPage,$instance, ServerRequestInterface $request){
		$this->_instance = $instance;		
		$this->_perPage = $perPage;
		$this->set_instance();
        $this->request = $request;
    }

	/**
	 * get_start
	 *
	 * creates the starting point for limiting the dataset
	 * @return numeric
	*/
	public function get_start(){
		return ($this->_page * $this->_perPage) - $this->_perPage;
	}

	/**
	 * set_instance
	 * 
	 * sets the instance parameter, if numeric value is 0 then set to 1
	 *
	 * @var numeric
	*/
	private function set_instance(){
		$this->_page = (int) (!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]); 
		$this->_page = ($this->_page == 0 ? 1 : $this->_page);
	}

	/**
	 * set_total
	 *
	 * collect a numberic value and assigns it to the totalRows
	 *
	 * @var numeric
	*/
	public function set_total($_totalRows){
		$this->_totalRows = $_totalRows;
	}

	/**
	 * get_limit
	 *
	 * returns the limit for the data source, calling the get_start method and passing in the number of items perp page
	 * 
	 * @return string
	*/
	public function get_limit(){
        	return $this->get_start().",$this->_perPage";
        }
	
	/**
     	* get_limit_keys
     	*
     	* returns an array of the offset and limit returned on each call
     	*
     	* @return string
    	*/
    	public function get_limit_keys(){
        	return ['offset' => $this->get_start(), 'limit' => $this->_perPage];
    	}

        /**
         * page_links
         *
         * create the html links for navigating through the dataset
         * 
         * @var sting $path optionally set the path for the link
         * @var sting $ext optionally pass in extra parameters to the GET
         * @return string returns the html menu
        */
	public function page_links($path='?',$ext=null)
	{
	    $adjacents = "2";
        $path = '?v='.$this->request->getQueryParams()['v'].'&';
	    $prev = $this->_page - 1;
	    $next = $this->_page + 1;
	    $lastpage = ceil($this->_totalRows/$this->_perPage);
	    $lpm1 = $lastpage - 1;

	    $pagination = "";
		if($lastpage > 1)
		{   
		    $pagination .= "<div class='paginate'><ul>";
		if ($this->_page > 1)
		    $pagination.= "<li class='previous'><a href='".$path."$this->_instance=$prev"."$ext'><span><i class=\"ri-arrow-left-fill\"></i></span></a></li>";
		else
		    $pagination.= "<li><a href='#'><span><i class=\"ri-arrow-left-fill\"></i></span></a><li>";

		if ($lastpage < 7 + ($adjacents * 2))
		{   
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<li class='active'><a href='#'>$counter</a></li>";
		else
		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
		}
		}
		elseif($lastpage > 5 + ($adjacents * 2))
		{
		if($this->_page < 1 + ($adjacents * 2))       
		{
		for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<li class='active'><a href='#'>$counter</a></li>";
		else
		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
		}
		    $pagination.= "...";
		    $pagination.= "<li><a href='".$path."$this->_instance=$lpm1"."$ext'>$lpm1</a></li>";
		    $pagination.= "<li><a href='".$path."$this->_instance=$lastpage"."$ext'>$lastpage</a></li>";
		}
		elseif($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2))
		{
		    $pagination.= "<li><a href='".$path."$this->_instance=1"."$ext'>1</a></li>";
		    $pagination.= "<li><a href='".$path."$this->_instance=2"."$ext'>2</a></li>";
		    $pagination.= "...";
		for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<span class='current'>$counter</span>";
		else
		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";
		}
		    $pagination.= "..";
		    $pagination.= "<li><a href='".$path."$this->_instance=$lpm1"."$ext'>$lpm1</a></li>";
		    $pagination.= "<li><a href='".$path."$this->_instance=$lastpage"."$ext'>$lastpage</a></li>";       
		}
		else
		{
		    $pagination.= "<li><a href='".$path."$this->_instance=1"."$ext'>1</a></li>";
		    $pagination.= "<li><a href='".$path."$this->_instance=2"."$ext'>2</a></li>";
		    $pagination.= "..";
		for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
		{
		if ($counter == $this->_page)
		    $pagination.= "<span class='current'>$counter</span>";
		else
		    $pagination.= "<li><a href='".$path."$this->_instance=$counter"."$ext'>$counter</a></li>";                   
		}
		}
		}

		if ($this->_page < $counter - 1)
		    $pagination.= "<li class='next'><a href='".$path."$this->_instance=$next"."$ext'><span><i class=\"ri-arrow-right-fill\"></i></span></a></li>";
		else
		    $pagination.= '<li><a href="#"><span><i class="ri-arrow-right-fill"></i></span></a></li>';
		    $pagination.= "</ul></div>\n";       
		}


	return $pagination;
	}
}