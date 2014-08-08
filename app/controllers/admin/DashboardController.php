<?php

namespace app\controllers\admin;
use Auth, BaseController, Form, Input, Redirect, Sentry, View, Log, FeedReader, Asset;

class DashboardController extends BaseController
{
    public function dashboard()
    {
    	//Get Server Hardware Info
    	// Get kernel info
		list($system, $host, $kernel) = explode(" ", exec("uname -a"), 5);
		// Grab uptime output

		// Get the kernel info, and grab the cool stuff
		$cpuinfo = file("/proc/cpuinfo");
		$total_cpu=0;

	//	dd($cpuinfo);

/*
		for ($i = 0; $i <= count($cpuinfo); $i++) {

				list($item, $data) = explode(":", $cpuinfo[$i], 2);
				$item = chop($item);
				$data = chop($data);

				if ($item == "processor") {
						$total_cpu++;
						$cpu_info = $total_cpu;
				}
				if ($item == "vendor_id") { $cpu_info .= $data; }
				if ($item == "model name") { $cpu_info .= $data; }
				if ($item == "cpu MHz") {
						$cpu_info .= " " . floor($data);
						$found_cpu = "yes";
				}
				if ($item == "cache size") { $cache = $data;}
				if ($item == "bogomips") { $bogomips = $data;}

		}
		/*
		if($found_cpu != "yes") { $cpu_info .= " <b>unknown</b>"; }
		$cpu_info .= " MHz Processor(s)\n";

		//Get the memory info, and grab the cool stuf
		*/
		$meminfo = file("/proc/meminfo");
		for ($i = 0; $i < count($meminfo); $i++) {
				list($item, $data) = explode(":", $meminfo[$i], 2);
				$item = chop($item);
				$data = chop($data);
				if ($item == "MemTotal") { $total_mem =$data;	}
				if ($item == "MemFree") { $free_mem = $data; }
				if ($item == "SwapTotal") { $total_swap = $data; }
				if ($item == "SwapFree") { $free_swap = $data; }
				if ($item == "Buffers") { $buffer_mem = $data; }
				if ($item == "Cached") { $cache_mem = $data; }
				if ($item == "MemShared") {$shared_mem = $data; }
		}
		$used_mem = ( $total_mem - $free_mem );
		$used_swap = ( $total_swap - $free_swap );
		$percent_free = round( $free_mem / $total_mem * 100 );
		$percent_used = round( $used_mem / $total_mem * 100 );
		$percent_swap = round( ( $total_swap - $free_swap ) / $total_swap * 100 );
		$percent_swap_free = round( $free_swap / $total_swap * 100 );
		$percent_buff = round( $buffer_mem / $total_mem * 100 );
		$percent_cach = round( $cache_mem / $total_mem * 100 );

		//$percent_shar = round( $shared_mem / $total_mem * 100 );
		//Now it's time to grab the cool stuff from the hard drive
		//This one is not quite as straight forward.....
		exec ("df", $x);
		$count = 1;
		/*while ($count < sizeof($x)) {
				list($drive[$count], $size[$count], $used[$count], $avail[$count], $percent[$count], $mount[$count]) = explode(" +", $x[$count]);
				$percent_part[$count] = str_replace( "%", "", $percent[$count] );
		$count++;
		}*/

		$rss=FeedReader::read('http://www.reportula.org/reportula/?feed=rss2');
  		return View::make('admin.dashboard', array (
        										'rss'               => $rss,
        										'uptime'            => (exec("uptime")),
        										'system'	        => $system,
        										'host'	            => $host,
        										'kernel'	        => $kernel,
        										'used_mem'          => ( $total_mem - $free_mem ),
												'used_swap'    		=> ( $total_swap - $free_swap ),
												'free_mem' 		    => $free_mem,
											));
    									}

}
