<?php
namespace weidizhang\PHPCurlWrapper;

class Behavior
{
	/*
	 * No headers will be unset after each request.
	 */
	const KEEP_HEADERS = 1;
	
	/*
	 * All headers will be unset after each request.
	 * Excludes unsetting User-Agent if it is set using Curl->setUserAgent and not Curl->setHeader.
	 */
	const CLEAR_HEADERS = 2;
}
?>